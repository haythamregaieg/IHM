<?php

namespace App\Http\Controllers;

use App\Models\Candidature;
use Illuminate\Http\Request;

class CandidatureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $candidatures = Candidature::with(['candidat', 'offre'])->get();
        return response()->json($candidatures);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'idCandidat' => 'required|exists:candidats,id',
            'idOffre' => 'required|exists:offres,idOffre',
            'dateCandidature' => 'required|date',
            'statut' => 'required|string|max:255',
            'lettreMotivation' => 'required|string',
        ]);

        $candidature = Candidature::create($validated);
        return response()->json($candidature, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $candidature = Candidature::with(['candidat', 'offre'])->findOrFail($id);
        return response()->json($candidature);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $candidature = Candidature::findOrFail($id);

        $validated = $request->validate([
            'statut' => 'sometimes|string|max:255',
        ]);

        $candidature->update($validated);
        return response()->json($candidature);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $candidature = Candidature::findOrFail($id);
        $candidature->delete();
        return response()->json(null, 204);
    }
}
