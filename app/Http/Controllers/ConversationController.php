<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    public function index()
    {
        $conversations = Auth::user()->conversations()
            ->with('agent')
            ->latest()
            ->get();

        return view('conversations.index', compact('conversations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|exists:agents,id',
        ]);

        $conversation = Conversation::create([
            'user_id' => Auth::id(),
            'agent_id' => $request->agent_id,
            'title' => 'New Conversation',
        ]);

        if ($request->wantsJson()) {
            return response()->json($conversation);
        }

        return redirect()->route('agents.chat', $conversation->agent_id);
    }

    public function show(Conversation $conversation)
    {
        $this->authorize('view', $conversation);

        $conversation->load(['messages', 'agent']);

        return view('chat', [
            'agent' => $conversation->agent,
            'conversation' => $conversation
        ]);
    }
}
