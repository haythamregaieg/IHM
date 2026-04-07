<?php
namespace App\Http\Controllers;

use App\Models\Candidature;
use App\Models\Offre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CandidatureController extends Controller
{
    // CANDIDAT: Soumettre une candidature
    public function store(Request $request)
    {
        $validated = $request->validate([
            'idOffre'          => 'required|exists:offres,idOffre',
            'lettreMotivation' => 'nullable|string',
            'lettre_file'      => 'nullable|file|mimes:pdf|max:2048',
            'cv'               => 'nullable|file|mimes:pdf|max:2048',
            'use_profile_cv'   => 'nullable|boolean',
            'telephone'        => 'required|string|max:20',
            'email_contact'    => 'required|email|max:100',
            'specialite'       => 'nullable|string|max:100',
            'niveau_experience'=> 'nullable|string|max:100',
            'update_profile'   => 'nullable|boolean',
        ]);

        $candidat = $request->user();
        $idCandidat = $candidat->idCandidat;

        // Verifier doublon
        $exists = Candidature::where('idCandidat', $idCandidat)
            ->where('idOffre', $validated['idOffre'])
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Vous avez deja postule a cette offre.'], 422);
        }

        // Verifier que l'offre est active
        $offre = Offre::findOrFail($validated['idOffre']);
        if ($offre->statut !== 'active' || $offre->dateLimite < now()->toDateString()) {
            return response()->json(['message' => 'Cette offre n\'est plus disponible.'], 422);
        }

        // Gestion du CV
        $cvPath = null;
        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('cvs/candidatures', 'public');
        } elseif ($request->use_profile_cv && $candidat->cv_path) {
            $cvPath = $candidat->cv_path;
        }

        if (!$cvPath) {
            return response()->json(['message' => 'Veuillez fournir un CV (fichier ou profil).'], 422);
        }

        // Gestion de la lettre de motivation (texte ou fichier)
        $lettreContenu = $validated['lettreMotivation'] ?? '';
        if ($request->hasFile('lettre_file')) {
            // Si c'est un fichier, on pourrait stocker le chemin ou extraire le texte. 
            // Ici, on va stocker le fichier et mettre un indicateur dans le texte si besoin, 
            // mais le modèle attend un text. On va adapter pour que cv_path et lettre_motivation soient gérés.
            $lmPath = $request->file('lettre_file')->store('lettres/candidatures', 'public');
            $lettreContenu = "[Fichier PDF joint] " . $lmPath;
        }

        if (empty($lettreContenu) && !$request->hasFile('lettre_file')) {
            return response()->json(['message' => 'Veuillez fournir une lettre de motivation.'], 422);
        }

        $candidature = Candidature::create([
            'idCandidat'      => $idCandidat,
            'idOffre'         => $validated['idOffre'],
            'dateCandidature' => now()->toDateString(),
            'statut'          => 'en_attente',
            'lettreMotivation'=> $lettreContenu,
            'cv_path'         => $cvPath,
            'telephone'       => $validated['telephone'],
            'email_contact'   => $validated['email_contact'],
            'specialite'      => $validated['specialite'] ?? null,
            'niveau_experience'=> $validated['niveau_experience'] ?? null,
        ]);

        // Mise à jour optionnelle du profil
        if ($request->update_profile) {
            $candidat->update([
                'specialite'       => $validated['specialite'] ?? $candidat->specialite,
                'niveau_experience'=> $validated['niveau_experience'] ?? $candidat->niveau_experience,
            ]);
        }

        return response()->json($candidature->load(['offre.entreprise']), 201);
    }

    // CANDIDAT: Mes candidatures
    public function mesCandidatures(Request $request)
    {
        $candidatures = Candidature::with(['offre.entreprise:idEntreprise,raison_sociale,logo_path', 'offre.domaine'])
            ->where('idCandidat', $request->user()->idCandidat)
            ->latest()
            ->get();
        return response()->json($candidatures);
    }

    // CANDIDAT: Retirer une candidature
    public function destroy(Request $request, $id)
    {
        $candidature = Candidature::where('idCandidature', $id)
            ->where('idCandidat', $request->user()->idCandidat)
            ->firstOrFail();

        if ($candidature->statut !== 'en_attente') {
            return response()->json(['message' => 'Impossible de retirer cette candidature.'], 422);
        }

        $candidature->delete();
        return response()->json(null, 204);
    }

    // ENTREPRISE: Toutes les candidatures reçues pour toutes les offres
    public function toutesParEntreprise(Request $request)
    {
        $idEntreprise = $request->user()->idEntreprise;
        $offresIds = Offre::where('idEntreprise', $idEntreprise)->pluck('idOffre');

        $query = Candidature::with(['candidat.certificats', 'candidat.experiences', 'candidat.domaine', 'offre'])
            ->whereIn('idOffre', $offresIds);

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        return response()->json($query->latest()->get());
    }

    // ENTREPRISE: Candidatures pour une offre spécifique
    public function parOffre(Request $request, $idOffre)
    {
        $candidatures = Candidature::with(['candidat.certificats', 'candidat.experiences', 'candidat.domaine'])
            ->where('idOffre', $idOffre)
            ->latest()
            ->get();
        return response()->json($candidatures);
    }

    // ENTREPRISE: Changer statut d'une candidature
    public function updateStatut(Request $request, $id)
    {
        $candidature = Candidature::findOrFail($id);
        $offre = Offre::where('idOffre', $candidature->idOffre)
            ->where('idEntreprise', $request->user()->idEntreprise)
            ->firstOrFail();

        $request->validate(['statut' => 'required|in:en_attente,en_cours,retenu,refuse']);
        $candidature->update(['statut' => $request->statut]);

        return response()->json($candidature->fresh());
    }

    // ADMIN: Toutes les candidatures
    public function adminIndex()
    {
        $candidatures = Candidature::with(['candidat:id,nom,prenom,email', 'offre:idOffre,titre'])
            ->latest()
            ->paginate(20);
        return response()->json($candidatures);
    }
}
