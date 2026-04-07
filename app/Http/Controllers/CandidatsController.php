<?php
namespace App\Http\Controllers;

use App\Models\Candidat;
use Illuminate\Http\Request;

class CandidatsController extends Controller
{
    // ADMIN: Liste de tous les candidats
    public function index()
    {
        $candidats = Candidat::with('domaine')->latest()->get();
        return response()->json($candidats);
    }

    public function show($id)
    {
        $candidat = Candidat::with(['domaine', 'certificats'])->findOrFail($id);
        return response()->json($candidat);
    }

    // ADMIN: Bloquer/desactiver (delete soft si utilise)
    public function destroy($id)
    {
        Candidat::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
