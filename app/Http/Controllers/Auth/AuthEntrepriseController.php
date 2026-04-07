<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Entreprise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class AuthEntrepriseController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'raisonSociale'     => 'required|string|max:255',
            'description'       => 'nullable|string',
            'identifiantLegal'  => 'required|string|unique:entreprises,identifiant_legal',
            'idDomaine'         => 'nullable|exists:domaines,idDomaine',
            'telephone'         => 'nullable|string|max:20',
            'email'             => 'required|email|unique:entreprises,email',
            'motDePasse'        => 'required|string|min:8|confirmed',
            'siteWeb'           => 'nullable|url|max:255',
            'pays'              => 'nullable|string|max:255',
            'ville'             => 'nullable|string|max:255',
        ]);

        $entreprise = Entreprise::create([
            'raison_sociale'    => $validated['raisonSociale'],
            'description'       => $validated['description'] ?? null,
            'identifiant_legal' => $validated['identifiantLegal'],
            'idDomaine'         => $validated['idDomaine'] ?? null,
            'telephone'         => $validated['telephone'] ?? null,
            'email'             => $validated['email'],
            'password'          => Hash::make($validated['motDePasse']),
            'site_web'          => $validated['siteWeb'] ?? null,
            'pays'              => $validated['pays'] ?? null,
            'ville'             => $validated['ville'] ?? null,
            'statut'            => 'en_attente',
        ]);
        
        \Log::info('EXTREME REGISTER LOG:');
        \Log::info('Email provided: [' . $validated['email'] . ']');
        \Log::info('MotDePasse length: ' . strlen($validated['motDePasse']));
        \Log::info('MotDePasse string: [' . $validated['motDePasse'] . ']');
        \Log::info('Hashed as: [' . $entreprise->password . ']');

        return response()->json([
            'message'    => 'Compte cree avec succes. En attente de validation par un administrateur.',
            'entreprise' => $entreprise,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'      => 'required|email',
            'motDePasse' => 'required|string',
        ]);

        $entreprise = Entreprise::where('email', $request->email)->first();
        
        \Log::info('EXTREME LOGIN LOG:');
        \Log::info('Email provided: [' . $request->email . ']');
        \Log::info('MotDePasse length: ' . strlen($request->motDePasse));
        \Log::info('MotDePasse string: [' . $request->motDePasse . ']');
        
        if (! $entreprise) {
            \Log::info('Entreprise not found in DB.');
        } else {
            \Log::info('Entreprise found. DB Email: [' . $entreprise->email . ']');
            \Log::info('DB Hash: [' . $entreprise->password . ']');
            \Log::info('DB Hash Length: ' . strlen($entreprise->password));
            $check = Hash::check($request->motDePasse, $entreprise->password);
            \Log::info('Password check result: ' . ($check ? 'true' : 'false'));
        }

        if (! $entreprise || ! Hash::check($request->motDePasse, $entreprise->password)) {
            throw ValidationException::withMessages([
                'email' => ['Les identifiants sont incorrects. (Assurez-vous d\'avoir sélectionné le bon profil : Candidat ou Entreprise).'],
            ]);
        }

        if ($entreprise->statut === 'en_attente') {
            return response()->json(['message' => 'Votre compte est en attente de validation.'], 403);
        }

        if ($entreprise->statut === 'bloque') {
            return response()->json(['message' => 'Votre compte a ete bloque. Contactez l\'administrateur.'], 403);
        }

        $token = $entreprise->createToken('entreprise-token', ['role:entreprise'])->plainTextToken;

        return response()->json([
            'entreprise' => $entreprise->load(['domaine', 'region']),
            'token'      => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken();
        if ($token) $token->delete();
        return response()->json(['message' => 'Deconnecte avec succes.']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user()->load(['domaine', 'region']));
    }

    public function updateProfile(Request $request)
    {
        $entreprise = $request->user();

        $validated = $request->validate([
            'description' => 'nullable|string',
            'idDomaine'   => 'nullable|exists:domaines,idDomaine',
            'idRegion'    => 'nullable|exists:regions,idRegion',
            'telephone'   => 'nullable|string|max:20',
            'site_web'    => 'nullable|url|max:255',
            'pays'        => 'nullable|string|max:255',
            'ville'       => 'nullable|string|max:255',
            'logo'        => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            if ($entreprise->logo_path) Storage::delete($entreprise->logo_path);
            $validated['logo_path'] = $request->file('logo')->store('logos/entreprises', 'public');
        }

        unset($validated['logo']);
        $entreprise->update($validated);

        return response()->json($entreprise->fresh()->load(['domaine', 'region']));
    }
}
