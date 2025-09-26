<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function index() {
        $conversations = Conversation::latest()->get();
        return view('chat.index', compact('conversations'));
    }

    public function show($id) {
        $conversation = Conversation::with('messages')->findOrFail($id);
        $conversations = Conversation::latest()->get();
        return view('chat.index', compact('conversations','conversation'));
    }

    public function new() {
        $conv = Conversation::create(['title' => 'Nova conversa']);
        return redirect()->route('conversation.show', $conv->id);
    }
}
