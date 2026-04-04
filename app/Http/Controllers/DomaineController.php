<?php

namespace App\Http\Controllers;

use App\Models\Domaine;
use Illuminate\Http\Request;

class DomaineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $domaines = Domaine::all();
        return response()->json($domaines);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        $domaine = Domaine::create($validated);
        return response()->json($domaine, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $domaine = Domaine::findOrFail($id);
        return response()->json($domaine);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $domaine = Domaine::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'sometimes|string|max:255',
        ]);

        $domaine->update($validated);
        return response()->json($domaine);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $domaine = Domaine::findOrFail($id);
        $domaine->delete();
        return response()->json(null, 204);
    }
}
