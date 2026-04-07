<?php
namespace App\Http\Controllers;

use App\Models\OffreEnregistre;
use Illuminate\Http\Request;

class OffreEnregistreController extends Controller
{
    // CANDIDAT: Ses favoris
    public function index(Request $request)
    {
        $idCandidat = $request->user()->idCandidat;
        $favoris = OffreEnregistre::with(['offre.entreprise:idEntreprise,raison_sociale', 'offre.domaine', 'offre.region'])
            ->where('idCandidat', $idCandidat)
            ->latest()
            ->get();

        // Vérifier si le candidat a déjà postulé pour chaque offre favorite
        $candidatures = \App\Models\Candidature::where('idCandidat', $idCandidat)
            ->whereIn('idOffre', $favoris->pluck('idOffre'))
            ->pluck('idOffre')
            ->toArray();

        $favoris->transform(function($fav) use ($candidatures) {
            if ($fav->offre) {
                $fav->offre->dejaPostule = in_array($fav->idOffre, $candidatures);
            }
            return $fav;
        });

        return response()->json($favoris);
    }

    // CANDIDAT: Ajouter aux favoris
    public function store(Request $request)
    {
        $idCandidat = $request->user()->idCandidat;
        $validated = $request->validate([
            'idOffre' => 'required|exists:offres,idOffre',
        ]);

        $exists = OffreEnregistre::where('idCandidat', $idCandidat)
            ->where('idOffre', $validated['idOffre'])
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Offre deja dans les favoris.'], 422);
        }

        $favori = OffreEnregistre::create([
            'idCandidat' => $idCandidat,
            'idOffre'    => $validated['idOffre'],
            'dateAjout'  => now(),
        ]);

        return response()->json($favori, 201);
    }

    // CANDIDAT: Retirer des favoris
    public function destroy(Request $request, $id)
    {
        $idCandidat = $request->user()->idCandidat;
        OffreEnregistre::where('id', $id)
            ->where('idCandidat', $idCandidat)
            ->firstOrFail()
            ->delete();
        return response()->json(null, 204);
    }

    // CANDIDAT: Toggle favori par idOffre
    public function toggle(Request $request, $idOffre)
    {
        $user = $request->user();
        if (!$user || !$user->idCandidat) {
            return response()->json(['message' => 'Utilisateur non identifié.'], 401);
        }

        $idCandidat = $user->idCandidat;
        $favori = OffreEnregistre::where('idCandidat', $idCandidat)
            ->where('idOffre', $idOffre)
            ->first();

        if ($favori) {
            $favori->delete();
            return response()->json(['saved' => false]);
        }

        try {
            OffreEnregistre::create([
                'idCandidat' => $idCandidat,
                'idOffre'    => $idOffre,
                'dateAjout'  => now()->toDateString(),
            ]);
            return response()->json(['saved' => true]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
