<?php

namespace App\Http\Controllers;

use App\Models\Certificat;
use Illuminate\Http\Request;

class CertificatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $certificats = Certificat::with('candidat')->get();
        return response()->json($certificats);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'idCandidat' => 'required|exists:candidats,id',
            'nom' => 'required|string|max:255',
            'organisme' => 'required|string|max:255',
            'dateObtention' => 'required|date',
        ]);

        $certificat = Certificat::create($validated);
        return response()->json($certificat, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $certificat = Certificat::with('candidat')->findOrFail($id);
        return response()->json($certificat);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $certificat = Certificat::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'sometimes|string|max:255',
            'organisme' => 'sometimes|string|max:255',
            'dateObtention' => 'sometimes|date',
        ]);

        $certificat->update($validated);
        return response()->json($certificat);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $certificat = Certificat::findOrFail($id);
        $certificat->delete();
        return response()->json(null, 204);
    }
}
