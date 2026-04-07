<?php
namespace App\Http\Middleware;

use App\Models\Candidat;
use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class CandidatMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        if (! $token) return response()->json(['message' => 'Non authentifie.'], 401);

        $pat = PersonalAccessToken::findToken($token);
        if (! $pat || ! $pat->tokenable instanceof Candidat) {
            return response()->json(['message' => 'Acces refuse. Candidat requis.'], 403);
        }

        $request->setUserResolver(fn() => $pat->tokenable);
        $request->attributes->set('user_type', 'candidat');
        return $next($request);
    }
}
