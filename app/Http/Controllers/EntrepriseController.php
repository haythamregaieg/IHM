<?php
namespace App\Http\Controllers;

use App\Models\Entreprise;
use Illuminate\Http\Request;

class EntrepriseController extends Controller
{
    // PUBLIC: Entreprises validees
    public function index()
    {
        $entreprises = Entreprise::with(['domaine', 'region'])
            ->where('statut', 'valide')
            ->get(['idEntreprise','raison_sociale','description','logo_path','site_web','idDomaine','idRegion']);
        return response()->json($entreprises);
    }

    public function show($id)
    {
        $entreprise = Entreprise::with(['domaine', 'region'])
            ->where('statut', 'valide')
            ->findOrFail($id);
        return response()->json($entreprise);
    }

    // ADMIN: Toutes les entreprises
    public function adminIndex()
    {
        $entreprises = Entreprise::with(['domaine', 'region'])->latest()->get();
        return response()->json($entreprises);
    }

    public function adminShow($id)
    {
        $entreprise = Entreprise::with(['domaine', 'region'])->findOrFail($id);
        return response()->json($entreprise);
    }

    // ADMIN: Valider/bloquer une entreprise
    public function updateStatut(Request $request, $id)
    {
        $entreprise = Entreprise::findOrFail($id);
        $request->validate(['statut' => 'required|in:en_attente,valide,bloque']);
        $entreprise->update(['statut' => $request->statut]);
        return response()->json($entreprise);
    }

    // ADMIN: Supprimer une entreprise
    public function destroy($id)
    {
        $entreprise = Entreprise::findOrFail($id);
        $entreprise->delete();
        return response()->json(null, 204);
    }
}
