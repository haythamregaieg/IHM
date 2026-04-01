<?php

namespace App\Http\Controllers;

use App\Models\Offre;
use Illuminate\Http\Request;

class OffreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $offres = Offre::with(['entreprise', 'domaine', 'categorie', 'region'])->get();
        return response()->json($offres);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'idEntreprise' => 'required|exists:entreprises,id',
            'idDomaine' => 'required|exists:domaines,idDomaine',
            'idCategorie' => 'required|exists:categories,idCategorie',
            'idRegion' => 'required|exists:regions,idRegion',
            'titre' => 'required|string|max:255',
            'typeContrat' => 'required|string|max:255',
            'description' => 'required|string',
            'competencesRequises' => 'required|string',
            'remuneration' => 'nullable|numeric',
            'datePublication' => 'nullable|date',
            'dateLimite' => 'required|date',
            'statut' => 'required|string|max:255',
            'localisation' => 'required|string|max:255',
        ]);

        $offre = Offre::create($validated);
        return response()->json($offre, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $offre = Offre::with(['entreprise', 'domaine', 'categorie', 'region'])->findOrFail($id);
        return response()->json($offre);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $offre = Offre::findOrFail($id);

        $validated = $request->validate([
            'idEntreprise' => 'sometimes|exists:entreprises,id',
            'idDomaine' => 'sometimes|exists:domaines,idDomaine',
            'idCategorie' => 'sometimes|exists:categories,idCategorie',
            'idRegion' => 'sometimes|exists:regions,idRegion',
            'titre' => 'sometimes|string|max:255',
            'typeContrat' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'competencesRequises' => 'sometimes|string',
            'remuneration' => 'nullable|numeric',
            'datePublication' => 'nullable|date',
            'dateLimite' => 'sometimes|date',
            'statut' => 'sometimes|string|max:255',
            'localisation' => 'sometimes|string|max:255',
        ]);

        $offre->update($validated);
        return response()->json($offre);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $offre = Offre::findOrFail($id);
        $offre->delete();
        return response()->json(null, 204);
    }
}
