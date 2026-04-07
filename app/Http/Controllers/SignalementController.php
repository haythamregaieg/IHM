<?php
namespace App\Http\Controllers;

use App\Models\Signalement;
use Illuminate\Http\Request;

class SignalementController extends Controller
{
    // CANDIDAT/ENTREPRISE: Creer un signalement
    public function store(Request $request)
    {
        $user     = $request->user();
        $userType = $request->attributes->get('user_type');

        $validated = $request->validate([
            'cible_type' => 'required|in:offre,candidat,entreprise,message',
            'cible_id'   => 'required|integer',
            'description'=> 'required|string',
        ]);

        $signalement = Signalement::create([
            'signaleur_type' => $userType,
            'signaleur_id'   => $user->getKey(),
            'cible_type'     => $validated['cible_type'],
            'cible_id'       => $validated['cible_id'],
            'description'    => $validated['description'],
            'date'           => now()->toDateString(),
            'statut'         => 'en_attente',
        ]);

        return response()->json($signalement, 201);
    }

    // ADMIN: Liste des signalements
    public function index(Request $request)
    {
        $query = Signalement::with(['signaleur', 'cible', 'admin'])->latest();
        if ($request->filled('statut')) $query->where('statut', $request->statut);
        return response()->json($query->get());
    }

    public function show($id)
    {
        $signalement = Signalement::with(['signaleur', 'cible', 'admin:idAdmin,nom,prenom'])->findOrFail($id);
        return response()->json($signalement);
    }

    // ADMIN: Traiter un signalement
    public function update(Request $request, $id)
    {
        $signalement = Signalement::findOrFail($id);
        $request->validate(['statut' => 'required|in:en_attente,traite,rejete']);
        
        $admin = $request->user();
        $adminId = $admin->idAdmin ?? $admin->getKey();

        if (!$adminId) {
            return response()->json(['message' => 'Admin ID non trouvé'], 401);
        }

        try {
            $signalement->update([
                'statut'  => $request->statut,
                'idAdmin' => $adminId,
            ]);

            // Rechargement avec précaution pour éviter les erreurs polymorphes si cible inexistante
            $signalement->load(['signaleur', 'cible', 'admin']);
            
            return response()->json($signalement);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors du traitement du signalement',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        Signalement::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
