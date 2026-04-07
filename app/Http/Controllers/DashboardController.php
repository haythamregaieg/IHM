<?php
namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Candidat;
use App\Models\Candidature;
use App\Models\Entreprise;
use App\Models\Offre;
use App\Models\Signalement;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // ADMIN: Statistiques generales
    public function adminStats()
    {
        return response()->json([
            'entreprises'         => Entreprise::count(),
            'entreprises_details' => [
                'total'     => Entreprise::count(),
                'validees'  => Entreprise::where('statut', 'valide')->count(),
                'en_attente'=> Entreprise::where('statut', 'en_attente')->count(),
                'bloquees'  => Entreprise::where('statut', 'bloque')->count(),
            ],
            'candidats'           => Candidat::count(),
            'offres'              => Offre::count(),
            'offres_details'      => [
                'total'   => Offre::count(),
                'actives' => Offre::where('statut', 'active')->count(),
                'expirees'=> Offre::where('statut', 'expiree')->count(),
            ],
            'candidatures'        => Candidature::count(),
            'signalements_attente'=> Signalement::where('statut', 'en_attente')->count(),
            'taux_conversion'     => round(
                Offre::where('statut', 'active')->withCount('candidatures')->get()
                    ->avg('candidatures_count') ?? 0, 2
            ),
        ]);
    }

    // ENTREPRISE: Dashboard recruteur
    public function entrepriseStats(Request $request)
    {
        $entrepriseId = $request->user()->idEntreprise;

        $offres = Offre::where('idEntreprise', $entrepriseId)
            ->withCount(['candidatures', 'candidatures as retenues' => fn($q) => $q->where('statut', 'retenu')])
            ->get();

        $candidatures = Candidature::with(['candidat', 'offre'])
            ->whereIn('idOffre', $offres->pluck('idOffre'))
            ->latest()
            ->get();

        return response()->json([
            'offres_total'      => $offres->count(),
            'offres_actives'    => $offres->where('statut', 'active')->count(),
            'candidatures_total'=> $offres->sum('candidatures_count'),
            'candidatures_retenues' => $offres->sum('retenues'),
            'offres_detail'     => $offres->map(fn($o) => [
                'idOffre'              => $o->idOffre,
                'titre'                => $o->titre,
                'statut'               => $o->statut,
                'dateLimite'           => $o->dateLimite,
                'candidatures_count'   => $o->candidatures_count,
                'retenues'             => $o->retenues,
            ]),
            'candidatures_recentes' => $candidatures->take(10),
        ]);
    }

    // CANDIDAT: Dashboard personnel
    public function candidatStats(Request $request)
    {
        $candidatId = $request->user()->idCandidat;

        $candidatures = Candidature::where('idCandidat', $candidatId)->get();

        return response()->json([
            'candidatures_total'  => $candidatures->count(),
            'en_attente'          => $candidatures->where('statut', 'en_attente')->count(),
            'en_cours'            => $candidatures->where('statut', 'en_cours')->count(),
            'retenues'            => $candidatures->where('statut', 'retenu')->count(),
            'refusees'            => $candidatures->where('statut', 'refuse')->count(),
            'favoris'             => \App\Models\OffreEnregistre::where('idCandidat', $candidatId)->count(),
        ]);
    }
}
