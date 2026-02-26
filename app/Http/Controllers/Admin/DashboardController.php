<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(\App\Services\LLMService $llmService)
    {
        $totalAgents = Agent::count();
        $totalUsers = User::count();
        $totalMessages = Message::count();

        // Menghitung token dari DB lokal
        $totalTokens = Message::sum('total_tokens');

        // Mengambil data real-time dari OpenRouter API
        $keyInfo = $llmService->getKeyInfo();
        $apiUsage = $keyInfo['usage'] ?? 0;
        $apiLimit = $keyInfo['limit'] ?? 0;

        // Kurs & Multiplier
        $exchangeRate = $llmService->getExchangeRate();
        $multiplier = $llmService->getProfitMultiplier();

        $apiUsageIdr = $apiUsage * $exchangeRate * $multiplier;
        $apiLimitIdr = $apiLimit * $exchangeRate;

        $isQuotaExceeded = ($apiUsage * $multiplier) >= $apiLimit && $apiLimit > 0;

        // Load Global Settings
        $defaultFreeTokens = \App\Models\Setting::get('default_free_tokens', 10000);

        return view('admin.dashboard', compact(
            'totalAgents',
            'totalUsers',
            'totalMessages',
            'totalTokens',
            'apiUsageIdr',
            'apiLimitIdr',
            'isQuotaExceeded',
            'defaultFreeTokens'
        ));
    }

    public function saveSettings(Request $request)
    {
        $request->validate([
            'default_free_tokens' => 'required|integer|min:0',
        ]);

        \App\Models\Setting::set('default_free_tokens', $request->input('default_free_tokens'));

        return back()->with('success', 'Pengaturan global berhasil disimpan.');
    }
}
