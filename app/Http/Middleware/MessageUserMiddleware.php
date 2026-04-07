<?php
namespace App\Http\Middleware;

use App\Models\Candidat;
use App\Models\Entreprise;
use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class MessageUserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        if (! $token) return response()->json(['message' => 'Non authentifie.'], 401);

        $pat = PersonalAccessToken::findToken($token);
        if (! $pat) return response()->json(['message' => 'Token invalide.'], 401);

        $tokenable = $pat->tokenable;

        if ($tokenable instanceof Entreprise) {
            $request->attributes->set('user_type', 'entreprise');
        } elseif ($tokenable instanceof Candidat) {
            $request->attributes->set('user_type', 'candidat');
        } else {
            return response()->json(['message' => 'Acces refuse.'], 403);
        }

        $request->setUserResolver(fn() => $tokenable);
        return $next($request);
    }
}
