<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $conversations = Conversation::latest()->get();
        return view('chat', compact('conversations'));
    }

    public function newConversation()
    {
        $conv = Conversation::create(['title' => 'Conversa com Ouee']);
        return redirect()->route('chat.view', $conv->id);
    }

    public function viewConversation($id)
    {
        $conversation = Conversation::with('messages')->findOrFail($id);
        $conversations = Conversation::latest()->get();

        return view('chat', compact('conversation', 'conversations'));
    }

    public function sendMessage(Request $request, $id)
    {
        $request->validate(['content' => 'required|string']);

        $conversation = Conversation::findOrFail($id);

        // mensagem do usuário
        Message::create([
            'conversation_id' => $conversation->id,
            'role' => 'user',
            'content' => $request->content
        ]);

        // resposta fake do "Agente Ouee"
        Message::create([
            'conversation_id' => $conversation->id,
            'role' => 'assistant',
            'content' => "🤖 Agente Ouee recebeu: " . $request->content
        ]);

        return redirect()->route('chat.view', $conversation->id);
    }
}
