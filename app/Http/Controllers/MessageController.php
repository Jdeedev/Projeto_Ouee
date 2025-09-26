<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Http\Request;
use App\Services\OpenAIService;

class MessageController extends Controller
{
    public function send(Request $request, $conversationId, OpenAIService $ai) {
        $request->validate(['content' => 'required|string']);
        $conversation = Conversation::findOrFail($conversationId);

        // salva mensagem do usuário
        $conversation->messages()->create([
            'role' => 'user',
            'content' => $request->input('content')
        ]);

        // monta histórico simples (opcional: limitar últimas N msgs)
        $history = $conversation->messages()->orderBy('created_at')->get(['role','content'])->toArray();

        // chama IA
        try {
            $reply = $ai->chat($history);
        } catch (\Throwable $e) {
            $reply = "Desculpe, houve um erro na IA: " . $e->getMessage();
        }

        // salva resposta
        $conversation->messages()->create([
            'role' => 'assistant',
            'content' => $reply
        ]);

        return redirect()->back();
    }
}
