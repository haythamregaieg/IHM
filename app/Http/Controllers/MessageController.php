<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $messages = Message::with(['entreprise', 'candidat'])->get();
        return response()->json($messages);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'idEntreprise' => 'required|exists:entreprises,id',
            'idCandidat' => 'required|exists:candidats,id',
            'contenu' => 'required|string',
            'dateEnvoi' => 'required|date',
        ]);

        $message = Message::create($validated);
        return response()->json($message, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $message = Message::with(['entreprise', 'candidat'])->findOrFail($id);
        return response()->json($message);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $message = Message::findOrFail($id);

        $validated = $request->validate([
            'contenu' => 'sometimes|string',
        ]);

        $message->update($validated);
        return response()->json($message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $message = Message::findOrFail($id);
        $message->delete();
        return response()->json(null, 204);
    }
}
