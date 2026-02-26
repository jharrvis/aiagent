<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
            'token_balance' => $user->token_balance,
        ];

        // 2. Token Usage Chart (Last 7 Days)
        $sevenDaysAgo = Carbon::now()->subDays(6)->startOfDay();
        $usageTrends = Message::whereHas('conversation', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->where('created_at', '>=', $sevenDaysAgo)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_tokens) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('total', 'date')
            ->toArray();

        // Fill missing days with 0
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $label = Carbon::now()->subDays($i)->format('D');
            $chartData['labels'][] = $label;
            $chartData['values'][] = $usageTrends[$date] ?? 0;
        }

        // 3. Top Agent (Most frequent conversation)
        $topAgent = Agent::whereHas('conversations', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->withCount([
                'conversations' => function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                }
            ])
            ->orderBy('conversations_count', 'desc')
            ->first();

        // 4. Aktivitas Terakhir (Quick Resume)
        $recentConversations = Conversation::with('agent')
            ->where('user_id', $user->id)
            ->orderBy('updated_at', 'desc')
            ->take(4)
            ->get();

        // 5. Rekomendasi Agen (Explore)
        $recommendedAgents = Agent::where('is_active', true)
            ->where('id', '!=', $topAgent?->id)
            ->inRandomOrder()
            ->take(6)
            ->get();

        return view('dashboard', compact('stats', 'recentConversations', 'recommendedAgents', 'chartData', 'topAgent'));
    }
}
