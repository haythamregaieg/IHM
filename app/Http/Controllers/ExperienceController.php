<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    // CANDIDAT: Ses expériences
    public function index(Request $request)
    {
        return response()->json(
            Experience::where('idCandidat', $request->user()->idCandidat)->orderBy('dateDebut', 'desc')->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'poste'       => 'required|string|max:255',
            'entreprise'  => 'required|string|max:255',
            'dateDebut'   => 'required|date',
            'dateFin'     => 'nullable|date|after_or_equal:dateDebut',
            'description' => 'nullable|string',
        ]);
        $validated['idCandidat'] = $request->user()->idCandidat;
        $experience = Experience::create($validated);
        return response()->json($experience, 201);
    }

    public function update(Request $request, $id)
    {
        $experience = Experience::where('id', $id)
            ->where('idCandidat', $request->user()->idCandidat)
            ->firstOrFail();

        $validated = $request->validate([
            'poste'       => 'sometimes|string|max:255',
            'entreprise'  => 'sometimes|string|max:255',
            'dateDebut'   => 'sometimes|date',
            'dateFin'     => 'nullable|date|after_or_equal:dateDebut',
            'description' => 'nullable|string',
        ]);

        $experience->update($validated);
        return response()->json($experience);
    }

    public function destroy(Request $request, $id)
    {
        $experience = Experience::where('id', $id)
            ->where('idCandidat', $request->user()->idCandidat)
            ->firstOrFail();
        $experience->delete();
        return response()->json(null, 204);
    }
}
