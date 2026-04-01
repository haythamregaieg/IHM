<?php

namespace App\Http\Controllers;

use App\Models\Candidats;
use Illuminate\Http\Request;

class CandidatsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $candidats = Candidats::all();
        return response()->json($candidats);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $candidat = Candidats::create($request->all());
        return response()->json($candidat, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $candidat = Candidats::findOrFail($id);
        return response()->json($candidat);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $candidat = Candidats::findOrFail($id);
        $candidat->update($request->all());
        return response()->json($candidat);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $candidat = Candidats::findOrFail($id);
        $candidat->delete();
        return response()->json(null, 204);
    }
}
