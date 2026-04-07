<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthAdminController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nom'        => 'required|string|max:255',
            'prenom'     => 'required|string|max:255',
            'email'      => 'required|email|unique:admins,email',
            'motDePasse' => 'required|string|min:8|confirmed',
        ]);

        $admin = Admin::create([
            'nom'     => $validated['nom'],
            'prenom'  => $validated['prenom'],
            'email'   => $validated['email'],
            'password'=> Hash::make($validated['motDePasse']),
        ]);

        $token = $admin->createToken('admin-token', ['role:admin'])->plainTextToken;

        return response()->json(['admin' => $admin, 'token' => $token], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'      => 'required|email',
            'motDePasse' => 'required|string',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (! $admin || ! Hash::check($request->motDePasse, $admin->password)) {
            throw ValidationException::withMessages([
                'email' => ['Les identifiants sont incorrects.'],
            ]);
        }

        $token = $admin->createToken('admin-token', ['role:admin'])->plainTextToken;

        return response()->json(['admin' => $admin, 'token' => $token]);
    }

    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken();
        if ($token) $token->delete();
        return response()->json(['message' => 'Deconnecte avec succes.']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
