<?php
namespace App\Http\Controllers;

use App\Models\Certificat;
use Illuminate\Http\Request;

class CertificatController extends Controller
{
    // CANDIDAT: Ses certificats
    public function index(Request $request)
    {
        return response()->json(
            Certificat::where('idCandidat', $request->user()->idCandidat)->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom'           => 'required|string|max:255',
            'organisme'     => 'required|string|max:255',
            'dateObtention' => 'required|date',
        ]);
        $validated['idCandidat'] = $request->user()->idCandidat;
        $certificat = Certificat::create($validated);
        return response()->json($certificat, 201);
    }

    public function update(Request $request, $id)
    {
        $certificat = Certificat::where('id', $id)
            ->where('idCandidat', $request->user()->idCandidat)
            ->firstOrFail();

        $validated = $request->validate([
            'nom'           => 'sometimes|string|max:255',
            'organisme'     => 'sometimes|string|max:255',
            'dateObtention' => 'sometimes|date',
        ]);

        $certificat->update($validated);
        return response()->json($certificat);
    }

    public function destroy(Request $request, $id)
    {
        $certificat = Certificat::where('id', $id)
            ->where('idCandidat', $request->user()->idCandidat)
            ->firstOrFail();
        $certificat->delete();
        return response()->json(null, 204);
    }
}
