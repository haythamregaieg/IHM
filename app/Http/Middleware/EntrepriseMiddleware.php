<?php
namespace App\Http\Middleware;

use App\Models\Entreprise;
use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class EntrepriseMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        if (! $token) return response()->json(['message' => 'Non authentifie.'], 401);

        $pat = PersonalAccessToken::findToken($token);
        if (! $pat || ! $pat->tokenable instanceof Entreprise) {
            return response()->json(['message' => 'Acces refuse. Entreprise requise.'], 403);
        }

        $request->setUserResolver(fn() => $pat->tokenable);
        $request->attributes->set('user_type', 'entreprise');
        return $next($request);
    }
}
