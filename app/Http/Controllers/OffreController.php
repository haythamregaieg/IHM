<?php
namespace App\Http\Controllers;

use App\Models\Offre;
use Illuminate\Http\Request;

class OffreController extends Controller
{
    // PUBLIC: Liste des offres
    public function index(Request $request)
    {
        $query = Offre::with(['entreprise:idEntreprise,raison_sociale,logo_path', 'domaine', 'region', 'categorie'])
            ->where('statut', 'active')
            ->where('dateLimite', '>=', now()->toDateString());

        if ($request->filled('q')) {
            $query->where('titre', 'like', '%' . $request->q . '%');
        }
        if ($request->filled('idDomaine')) {
            $query->where('idDomaine', $request->idDomaine);
        }
        if ($request->filled('idRegion')) {
            $query->where('idRegion', $request->idRegion);
        }
        if ($request->filled('typeContrat')) {
            $query->where('typeContrat', $request->typeContrat);
        }
        if ($request->filled('remunMin')) {
            $query->where('remuneration', '>=', $request->remunMin);
        }

        // Tri
        $sort = $request->get('sort', 'latest');
        if ($sort === 'salary') {
            $query->orderBy('remuneration', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $paginator = $query->paginate(10);

        // Vérifier si le candidat a déjà postulé pour chaque offre
        if ($request->user() && $request->attributes->get('user_type') === 'candidat') {
            $candidatures = \App\Models\Candidature::where('idCandidat', $request->user()->idCandidat)
                ->whereIn('idOffre', $paginator->pluck('idOffre'))
                ->pluck('idOffre')
                ->toArray();
            
            $paginator->getCollection()->transform(function($offre) use ($candidatures) {
                $offre->dejaPostule = in_array($offre->idOffre, $candidatures);
                return $offre;
            });
        }

        return response()->json($paginator);
    }

    public function show(Request $request, $id)
    {
        $offre = Offre::with(['entreprise', 'domaine', 'region', 'categorie'])
            ->findOrFail($id);
        
        $offre->increment('vues');

        // Vérifier si le candidat a déjà postulé
        $dejaPostule = false;
        if ($request->user() && $request->attributes->get('user_type') === 'candidat') {
            $dejaPostule = \App\Models\Candidature::where('idOffre', $id)
                ->where('idCandidat', $request->user()->idCandidat)
                ->exists();
        }

        $data = $offre->toArray();
        $data['dejaPostule'] = $dejaPostule;
        
        return response()->json($data);
    }

    // ENTREPRISE: Ses propres offres
    public function mesOffres(Request $request)
    {
        $offres = Offre::withCount('candidatures')
            ->with(['region', 'domaine', 'categorie'])
            ->where('idEntreprise', $request->user()->idEntreprise)
            ->latest()
            ->get();
        return response()->json($offres);
    }

    // ENTREPRISE: Ajouter une offre
    public function store(Request $request)
    {
        $validated = $request->validate([
            'idDomaine'           => 'required|exists:domaines,idDomaine',
            'idCategorie'         => 'required|exists:categories,idCategorie',
            'idRegion'            => 'required|exists:regions,idRegion',
            'titre'               => 'required|string|max:255',
            'typeContrat'         => 'required|string',
            'description'         => 'required|string',
            'competencesRequises' => 'required|string',
            'experience'          => 'required|string',
            'mode_travail'        => 'required|string',
            'niveau_etude'        => 'required|string',
            'remuneration'        => 'nullable|numeric',
            'dateLimite'          => 'required|date|after_or_equal:today',
            'localisation'        => 'required|string',
        ]);

        $offre = Offre::create([
            ...$validated,
            'idEntreprise'    => $request->user()->idEntreprise,
            'datePublication' => now()->toDateString(),
            'statut'          => 'active',
            'vues'            => 0,
        ]);

        return response()->json($offre, 201);
    }

    // ENTREPRISE: Modifier une offre
    public function update(Request $request, $id)
    {
        $offre = Offre::where('idOffre', $id)
            ->where('idEntreprise', $request->user()->idEntreprise)
            ->firstOrFail();

        $validated = $request->validate([
            'idDomaine'           => 'required|exists:domaines,idDomaine',
            'idCategorie'         => 'required|exists:categories,idCategorie',
            'idRegion'            => 'required|exists:regions,idRegion',
            'titre'               => 'required|string|max:255',
            'typeContrat'         => 'required|string',
            'description'         => 'sometimes|string',
            'competencesRequises' => 'sometimes|string',
            'experience'          => 'sometimes|string',
            'mode_travail'        => 'sometimes|string',
            'niveau_etude'        => 'sometimes|string',
            'remuneration'        => 'nullable|numeric',
            'dateLimite'          => 'required|date',
            'localisation'        => 'required|string',
        ]);

        $offre->update($validated);

        return response()->json($offre);
    }

    // ENTREPRISE: Suspendre/Réactiver
    public function suspendre(Request $request, $id)
    {
        $offre = Offre::where('idOffre', $id)
            ->where('idEntreprise', $request->user()->idEntreprise)
            ->firstOrFail();

        // Désactiver une offre : seulement possible après la date limite
        if ($offre->statut === 'active') {
            if (now()->toDateString() < $offre->dateLimite) {
                return response()->json([
                    'message' => "Vous ne pouvez désactiver cette offre qu'après la date limite (" . \Carbon\Carbon::parse($offre->dateLimite)->format('d/m/Y') . ")."
                ], 422);
            }
            $offre->update(['statut' => 'suspendue']);
        } else {
            // Réactiver
            $offre->update(['statut' => 'active']);
        }

        return response()->json($offre);
    }

    // ENTREPRISE: Supprimer
    public function destroy(Request $request, $id)
    {
        $offre = Offre::where('idOffre', $id)
            ->where('idEntreprise', $request->user()->idEntreprise)
            ->firstOrFail();

        $offre->delete();
        return response()->json(null, 204);
    }

    // ADMIN: Liste complete
    public function adminIndex()
    {
        $offres = Offre::with(['entreprise', 'domaine'])->latest()->get();
        return response()->json($offres);
    }

    // ADMIN: Valider/Rejeter
    public function adminUpdateStatut(Request $request, $id)
    {
        $offre = Offre::findOrFail($id);
        $request->validate(['statut' => 'required|in:active,suspendue,cloturee']);
        $offre->update(['statut' => $request->statut]);
        return response()->json($offre);
    }
}
