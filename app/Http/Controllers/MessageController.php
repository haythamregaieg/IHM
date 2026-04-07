<?php
namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    // Conversation entre une entreprise et un candidat
    public function conversation(Request $request, $interlocuteurId)
    {
        $user = $request->user();
        $userType = $request->attributes->get('user_type');

        $query = Message::with(['entreprise:idEntreprise,raison_sociale', 'candidat:idCandidat,nom,prenom']);

        if ($userType === 'entreprise') {
            $query->where('idEntreprise', $user->idEntreprise)->where('idCandidat', $interlocuteurId);
        } else {
            $query->where('idCandidat', $user->idCandidat)->where('idEntreprise', $interlocuteurId);
        }

        $messages = $query->orderBy('dateEnvoi')->get();

        // Marquer comme lus
        Message::whereIn('idMessage', $messages->pluck('idMessage'))
            ->where('lu', false)
            ->where('expediteur', $userType === 'entreprise' ? 'candidat' : 'entreprise')
            ->update(['lu' => true]);

        return response()->json($messages);
    }

    // Envoyer un message
    public function store(Request $request)
    {
        $user     = $request->user();
        $userType = $request->attributes->get('user_type');

        $validated = $request->validate([
            'contenu'      => 'required|string',
            'idEntreprise' => 'required_if:user_type,candidat|exists:entreprises,idEntreprise',
            'idCandidat'   => 'required_if:user_type,entreprise|exists:candidats,idCandidat',
        ]);

        $message = Message::create([
            'idEntreprise' => $userType === 'entreprise' ? $user->idEntreprise : $validated['idEntreprise'],
            'idCandidat'   => $userType === 'candidat'   ? $user->idCandidat   : $validated['idCandidat'],
            'contenu'      => $validated['contenu'],
            'dateEnvoi'    => now(),
            'expediteur'   => $userType,
            'lu'           => false,
        ]);

        return response()->json($message->load(['entreprise:idEntreprise,raison_sociale', 'candidat:idCandidat,nom,prenom']), 201);
    }

    // Liste des conversations (pour entreprise ou candidat)
    public function mesConversations(Request $request)
    {
        $user     = $request->user();
        $userType = $request->attributes->get('user_type');

        try {
            if ($userType === 'entreprise') {
                $lastMessages = Message::where('idEntreprise', $user->idEntreprise)
                    ->selectRaw('MAX(idMessage) as last_id')
                    ->groupBy('idCandidat')
                    ->pluck('last_id');

                $conversations = Message::with('candidat:idCandidat,nom,prenom')
                    ->whereIn('idMessage', $lastMessages)
                    ->latest('dateEnvoi')
                    ->get();
            } else {
                $lastMessages = Message::where('idCandidat', $user->idCandidat)
                    ->selectRaw('MAX(idMessage) as last_id')
                    ->groupBy('idEntreprise')
                    ->pluck('last_id');

                $conversations = Message::with('entreprise:idEntreprise,raison_sociale,logo_path')
                    ->whereIn('idMessage', $lastMessages)
                    ->latest('dateEnvoi')
                    ->get();
            }

            return response()->json($conversations);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur serveur: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        $message = Message::findOrFail($id);
        $user = $request->user();
        $userType = $request->attributes->get('user_type');

        $authorized = ($userType === 'entreprise' && $message->idEntreprise === $user->idEntreprise) ||
                      ($userType === 'candidat'   && $message->idCandidat   === $user->idCandidat);

        if (! $authorized) return response()->json(['message' => 'Non autorise.'], 403);

        $message->delete();
        return response()->json(null, 204);
    }

    // Nombre de messages non lus pour l'utilisateur connecté
    public function unreadCount(Request $request)
    {
        $user = $request->user();
        $userType = $request->attributes->get('user_type');

        $query = Message::where('lu', false);

        if ($userType === 'entreprise') {
            $query->where('idEntreprise', $user->idEntreprise)->where('expediteur', 'candidat');
        } else {
            $query->where('idCandidat', $user->idCandidat)->where('expediteur', 'entreprise');
        }

        return response()->json(['count' => $query->count()]);
    }
}
