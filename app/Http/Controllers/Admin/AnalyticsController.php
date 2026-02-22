<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\LLMService;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function __construct(
        private LLMService $llmService
    ) {}

    /**
     * Display analytics dashboard
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $selectedDate = $request->input('date');
        $selectedModel = $request->input('model');

        // Get key info for quota details
        $keyInfo = $this->llmService->getKeyInfo();
        $exchangeRate = $this->llmService->getExchangeRate();
        $multiplier = $this->llmService->getProfitMultiplier();

        // Calculate costs in IDR
        $apiUsage = $keyInfo['usage'] ?? 0;
        $apiLimit = $keyInfo['limit'] ?? 0;
        $apiUsageIdr = $apiUsage * $exchangeRate * $multiplier;
        $apiLimitIdr = $apiLimit * $exchangeRate;

        // Get model usage statistics
        $modelStats = $this->llmService->getModelUsageStats();

        // Get daily usage for charts
        $dailyUsage = $this->llmService->getDailyUsage();

        // Get detailed activity logs
        $activityResponse = $this->llmService->getUserActivity($selectedDate);
        $activityLogs = $activityResponse['data'] ?? [];
        $activityError = $activityResponse['error'] ?? null;

        // Filter by model if selected
        if ($selectedModel && !$activityError) {
            $activityLogs = array_filter($activityLogs, function ($log) use ($selectedModel) {
                return ($log['model'] ?? '') === $selectedModel;
            });
        }

        // Get unique models for filter dropdown
        $uniqueModels = array_unique(array_column($activityLogs, 'model'));
        sort($uniqueModels);

        // Calculate summary statistics from activity logs
        $summaryStats = $this->calculateSummaryStats($activityLogs, $exchangeRate, $multiplier);

        return view('admin.analytics.index', compact(
            'keyInfo',
            'apiUsage',
            'apiLimit',
            'apiUsageIdr',
            'apiLimitIdr',
            'modelStats',
            'dailyUsage',
            'activityLogs',
            'activityError',
            'uniqueModels',
            'selectedDate',
            'selectedModel',
            'summaryStats',
            'exchangeRate',
            'multiplier'
        ));
    }

    /**
     * Calculate summary statistics from activity logs
     */
    private function calculateSummaryStats(array $logs, float $exchangeRate, float $multiplier): array
    {
        $totalRequests = 0;
        $totalPromptTokens = 0;
        $totalCompletionTokens = 0;
        $totalReasoningTokens = 0;
        $totalUsage = 0;

        foreach ($logs as $log) {
            $totalRequests += (int) ($log['requests'] ?? 0);
            $totalPromptTokens += (int) ($log['prompt_tokens'] ?? 0);
            $totalCompletionTokens += (int) ($log['completion_tokens'] ?? 0);
            $totalReasoningTokens += (int) ($log['reasoning_tokens'] ?? 0);
            $totalUsage += (float) ($log['usage'] ?? 0);
        }

        return [
            'total_requests' => $totalRequests,
            'total_prompt_tokens' => $totalPromptTokens,
            'total_completion_tokens' => $totalCompletionTokens,
            'total_reasoning_tokens' => $totalReasoningTokens,
            'total_tokens' => $totalPromptTokens + $totalCompletionTokens + $totalReasoningTokens,
            'total_usage_usd' => $totalUsage,
            'total_usage_idr' => $totalUsage * $exchangeRate * $multiplier,
        ];
    }

    /**
     * Get analytics data as JSON (for AJAX requests)
     */
    public function data(Request $request)
    {
        $selectedDate = $request->input('date');
        
        $activityResponse = $this->llmService->getUserActivity($selectedDate);
        $modelStats = $this->llmService->getModelUsageStats();
        $dailyUsage = $this->llmService->getDailyUsage();

        return response()->json([
            'activity' => $activityResponse,
            'model_stats' => $modelStats,
            'daily_usage' => $dailyUsage,
        ]);
    }
}
