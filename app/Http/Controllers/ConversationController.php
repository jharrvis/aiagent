<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    public function index()
    {
        $conversations = Auth::user()->conversations()
            ->with(['agent', 'messages'])
            ->latest('updated_at')
            ->get();

        $groupedConversations = $conversations->groupBy(function ($conv) {
            if ($conv->updated_at->isToday()) {
                return 'Hari Ini';
            } elseif ($conv->updated_at->isYesterday()) {
                return 'Kemarin';
            } elseif ($conv->updated_at->isCurrentWeek()) {
                return 'Minggu Ini';
            } elseif ($conv->updated_at->isCurrentMonth()) {
                return 'Bulan Ini';
            }
            return $conv->updated_at->translatedFormat('F Y');
        });

        return view('conversations.index', compact('groupedConversations'));
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

    public function destroy(Conversation $conversation)
    {
        $this->authorize('view', $conversation);

        $agentId = $conversation->agent_id;
        $conversation->delete();

        // If request expects JSON (AJAX), return JSON response
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Percakapan berhasil dihapus.'
            ]);
        }

        // If from the list view (conversations), redirect back there
        if (request()->headers->get('referer') == route('conversations.index')) {
            return redirect()->route('conversations.index')
                ->with('status', 'Percakapan berhasil dihapus.');
        }

        return redirect()->route('agents.chat', $agentId)
            ->with('status', 'Percakapan berhasil dihapus.');
    }

    public function download(Conversation $conversation)
    {
        $this->authorize('view', $conversation);
        $conversation->load(['messages', 'agent']);

        $chatText = "# Percakapan dengan {$conversation->agent->name}\nTanggal: " . now()->format('d/m/Y') . "\n\n";

        foreach ($conversation->messages as $msg) {
            $role = $msg->role === 'user' ? 'Anda' : $conversation->agent->name;
            $time = $msg->created_at->format('H:i');
            $chatText .= "### {$role} ({$time})\n{$msg->content}\n\n---\n\n";
        }

        $filename = "Chat_{$conversation->agent->name}_" . now()->format('Y-m-d') . ".md";

        return response($chatText)
            ->header('Content-Type', 'text/markdown')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }
}
