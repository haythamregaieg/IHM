<?php

namespace App\Http\Controllers;

use App\Models\Signalement;
use Illuminate\Http\Request;

class SignalementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $signalements = Signalement::with('admin')->get();
        return response()->json($signalements);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'idAdmin' => 'required|exists:admins,id',
            'description' => 'required|string',
            'date' => 'required|date',
            'statut' => 'required|string|max:255',
        ]);

        $signalement = Signalement::create($validated);
        return response()->json($signalement, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $signalement = Signalement::with('admin')->findOrFail($id);
        return response()->json($signalement);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $signalement = Signalement::findOrFail($id);

        $validated = $request->validate([
            'statut' => 'sometimes|string|max:255',
        ]);

        $signalement->update($validated);
        return response()->json($signalement);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $signalement = Signalement::findOrFail($id);
        $signalement->delete();
        return response()->json(null, 204);
    }
}
