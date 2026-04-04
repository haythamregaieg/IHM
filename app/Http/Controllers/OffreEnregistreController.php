<?php

namespace App\Http\Controllers;

use App\Models\OffreEnregistre;
use Illuminate\Http\Request;

class OffreEnregistreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $offresEnregistrees = OffreEnregistre::with(['candidat', 'offre'])->get();
        return response()->json($offresEnregistrees);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'idCandidat' => 'required|exists:candidats,id',
            'idOffre' => 'required|exists:offres,idOffre',
            'dateAjout' => 'required|date',
        ]);

        $offreEnregistree = OffreEnregistre::create($validated);
        return response()->json($offreEnregistree, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $offreEnregistree = OffreEnregistre::with(['candidat', 'offre'])->findOrFail($id);
        return response()->json($offreEnregistree);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Favoris functionally are usually not "updated" but we'll include it for completeness
        $offreEnregistree = OffreEnregistre::findOrFail($id);
        $offreEnregistree->update($request->all());
        return response()->json($offreEnregistree);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $offreEnregistree = OffreEnregistre::findOrFail($id);
        $offreEnregistree->delete();
        return response()->json(null, 204);
    }
}
