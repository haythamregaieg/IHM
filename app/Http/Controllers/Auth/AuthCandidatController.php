<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Candidat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class AuthCandidatController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nom'               => 'required|string|max:255',
            'prenom'            => 'required|string|max:255',
            'email'             => 'required|email|unique:candidats,email',
            'motDePasse'        => 'required|string|min:8|confirmed',
            'idDomaine'         => 'nullable|exists:domaines,idDomaine',
            'niveauEtude'       => 'nullable|string|max:255',
            'niveauExperience'  => 'nullable|string|max:255',
            'localisation'      => 'nullable|string|max:255',
            'specialite'        => 'nullable|string|max:255',
        ]);

        $candidat = Candidat::create([
            'nom'               => $validated['nom'],
            'prenom'            => $validated['prenom'],
            'email'             => $validated['email'],
            'password'          => Hash::make($validated['motDePasse']),
            'idDomaine'         => $validated['idDomaine'] ?? null,
            'niveau_etudes'     => $validated['niveauEtude'] ?? null,
            'niveau_experience' => $validated['niveauExperience'] ?? null,
            'localisation'      => $validated['localisation'] ?? null,
            'specialite'        => $validated['specialite'] ?? null,
        ]);

        $token = $candidat->createToken('candidat-token', ['role:candidat'])->plainTextToken;

        return response()->json(['candidat' => $candidat, 'token' => $token], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'      => 'required|email',
            'motDePasse' => 'required|string',
        ]);

        $candidat = Candidat::where('email', $request->email)->first();

        if (! $candidat || ! Hash::check($request->motDePasse, $candidat->password)) {
            throw ValidationException::withMessages([
                'email' => ['Les identifiants sont incorrects.'],
            ]);
        }

        $token = $candidat->createToken('candidat-token', ['role:candidat'])->plainTextToken;

        return response()->json(['candidat' => $candidat->load('domaine'), 'token' => $token]);
    }

    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken();
        if ($token) $token->delete();
        return response()->json(['message' => 'Deconnecte avec succes.']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user()->load(['domaine', 'certificats', 'region']));
    }

    public function updateProfile(Request $request)
    {
        $candidat = $request->user();

        $validated = $request->validate([
            'nom'               => 'sometimes|string|max:255',
            'prenom'            => 'sometimes|string|max:255',
            'telephone'         => 'nullable|string|max:20',
            'idDomaine'         => 'nullable|exists:domaines,idDomaine',
            'idRegion'          => 'nullable|exists:regions,idRegion',
            'niveau_etudes'     => 'nullable|string|max:255',
            'niveau_experience' => 'nullable|string|max:255',
            'localisation'      => 'nullable|string|max:255',
            'specialite'        => 'nullable|string|max:255',
            'photo'             => 'nullable|image|max:2048',
            'cv'                => 'nullable|mimes:pdf,doc,docx|max:5120',
        ]);

        if ($request->hasFile('photo')) {
            if ($candidat->photo_path) Storage::disk('public')->delete($candidat->photo_path);
            $validated['photo_path'] = $request->file('photo')->store('photos/candidats', 'public');
        }

        if ($request->hasFile('cv')) {
            if ($candidat->cv_path) Storage::disk('public')->delete($candidat->cv_path);
            $validated['cv_path'] = $request->file('cv')->store('cvs', 'public');
        }

        unset($validated['photo'], $validated['cv']);
        $candidat->update($validated);

        return response()->json($candidat->fresh()->load(['domaine', 'region']));
    }

    public function importCV(Request $request)
    {
        $candidat = $request->user();
        
        $request->validate([
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5120',
        ]);

        if ($candidat->cv_path) {
            Storage::disk('public')->delete($candidat->cv_path);
        }

        $path = $request->file('cv')->store('cvs', 'public');
        $candidat->update(['cv_path' => $path]);

        return response()->json(['cv_path' => $path, 'message' => 'CV importé avec succès.']);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        $request->user()->update(['password' => Hash::make($request->password)]);

        return response()->json(['message' => 'Mot de passe mis a jour.']);
    }
}
