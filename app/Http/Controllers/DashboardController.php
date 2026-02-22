<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Statistik Penggunaan
        $stats = [
            'total_messages' => Message::whereHas('conversation', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->count(),
            'total_images' => Message::whereHas('conversation', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->whereNotNull('metadata->image_url')->count(),
            'active_agents' => Conversation::where('user_id', $user->id)
                ->distinct('agent_id')
                ->count('agent_id'),
        ];

        // 2. Aktivitas Terakhir (Quick Resume)
        $recentConversations = Conversation::with('agent')
            ->where('user_id', $user->id)
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        // 3. Rekomendasi Agen (Explore)
        $recommendedAgents = Agent::where('is_active', true)
            ->inRandomOrder()
            ->take(6)
            ->get();

        return view('dashboard', compact('stats', 'recentConversations', 'recommendedAgents'));
    }
}
