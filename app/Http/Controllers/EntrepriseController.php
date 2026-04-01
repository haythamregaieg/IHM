<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use Illuminate\Http\Request;

class EntrepriseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $entreprises = Entreprise::all();
        return response()->json($entreprises);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Add enterprise specific validation once fields are defined in migrations later
        $entreprise = Entreprise::create($request->all());
        return response()->json($entreprise, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $entreprise = Entreprise::findOrFail($id);
        return response()->json($entreprise);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $entreprise = Entreprise::findOrFail($id);
        $entreprise->update($request->all());
        return response()->json($entreprise);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $entreprise = Entreprise::findOrFail($id);
        $entreprise->delete();
        return response()->json(null, 204);
    }
}
