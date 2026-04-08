<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Candidat;
use App\Models\Entreprise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UnifiedAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'      => 'required|email',
            'motDePasse' => 'required|string',
        ]);

        $email = $request->email;
        $password = $request->motDePasse;

        // 1. Check Admin
        $admin = Admin::where('email', $email)->first();
        if ($admin && Hash::check($password, $admin->password)) {
            $token = $admin->createToken('admin-token', ['role:admin'])->plainTextToken;
            return response()->json([
                'type' => 'admin',
                'user' => $admin,
                'token' => $token
            ]);
        }

        // 2. Check Entreprise
        $entreprise = Entreprise::where('email', $email)->first();
        if ($entreprise && Hash::check($password, $entreprise->password)) {
            if ($entreprise->statut === 'en_attente') {
                return response()->json([
                    'message' => 'Votre compte entreprise est en attente de validation par un administrateur.'
                ], 403);
            }
            if ($entreprise->statut === 'bloque') {
                return response()->json([
                    'message' => 'Votre compte entreprise a été bloqué. Veuillez contacter le support.'
                ], 403);
            }
            $token = $entreprise->createToken('entreprise-token', ['role:entreprise'])->plainTextToken;
            return response()->json([
                'type' => 'entreprise',
                'user' => $entreprise->load('domaine'),
                'token' => $token
            ]);
        }

        // 3. Check Candidat
        $candidat = Candidat::where('email', $email)->first();
        if ($candidat && Hash::check($password, $candidat->password)) {
            $token = $candidat->createToken('candidat-token', ['role:candidat'])->plainTextToken;
            return response()->json([
                'type' => 'candidat',
                'user' => $candidat->load('domaine'),
                'token' => $token
            ]);
        }

        throw ValidationException::withMessages([
            'email' => ['Les identifiants sont incorrects.'],
        ]);
    }
}
