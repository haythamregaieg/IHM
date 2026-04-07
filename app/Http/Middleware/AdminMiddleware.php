<?php
namespace App\Http\Middleware;

use App\Models\Admin;
use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        if (! $token) return response()->json(['message' => 'Non authentifie.'], 401);

        $pat = PersonalAccessToken::findToken($token);
        if (! $pat || ! $pat->tokenable instanceof Admin) {
            return response()->json(['message' => 'Acces refuse. Administrateur requis.'], 403);
        }

        $request->setUserResolver(fn() => $pat->tokenable);
        return $next($request);
    }
}
