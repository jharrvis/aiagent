<?php

namespace App\Services;

use App\Models\Agent;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LLMService
{
    private string $baseUrl;
    private string $apiKey;
    private ?string $managementKey;

    public function __construct()
    {
        $this->baseUrl = config('services.openrouter.base_url');
        $this->apiKey = config('services.openrouter.api_key');
        $this->managementKey = config('services.openrouter.management_key');
    }

    public function chat(\App\Models\Agent $agent, $messages, $context = null): array
    {
        $systemPrompt = $agent->system_prompt;

        if ($context) {
            $systemPrompt .= "\n\nRelevant context from knowledge base:\n" . $context;
        }

        $chatMessages = $messages->map(function ($message) {
            return [
                'role' => $message->role,
                'content' => $message->content,
            ];
        })->toArray();

        array_unshift($chatMessages, [
            'role' => 'system',
            'content' => $systemPrompt,
        ]);

        $startTime = microtime(true);

        try {
            // Check if using Perplexity model for citations support
            $isPerplexity = str_starts_with($agent->openrouter_model_id, 'perplexity/');

            $requestBody = [
                'model' => $agent->openrouter_model_id,
                'messages' => $chatMessages,
                'temperature' => (float) $agent->temperature,
            ];

            // Add perplexity-specific parameters
            if ($isPerplexity) {
                $requestBody['return_citations'] = true;
                $requestBody['return_reasoning'] = true;
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'HTTP-Referer' => config('app.url'),
                'X-Title' => config('app.name'),
            ])->post($this->baseUrl . '/chat/completions', $requestBody);

            $duration = round((microtime(true) - $startTime) * 1000, 2);
            Log::info('LLM call completed', [
                'model' => $agent->openrouter_model_id,
                'duration_ms' => $duration,
            ]);

            if ($response->failed()) {
                Log::error('LLM call failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return [
                    'content' => 'Sorry, I encountered an error processing your request. Please try again.',
                    'usage' => [],
                ];
            }

            $result = $response->json();
            $content = $result['choices'][0]['message']['content'] ?? '';
            $usage = $result['usage'] ?? [];

            // Extract citations if available (Perplexity)
            $citations = [];
            if (isset($result['citations']) && is_array($result['citations'])) {
                $citations = $result['citations'];
            }

            // Extract reasoning if available
            $reasoning = '';
            if (isset($result['choices'][0]['message']['reasoning'])) {
                $reasoning = $result['choices'][0]['message']['reasoning'];
            }

            return [
                'content' => $content,
                'usage' => $usage,
                'citations' => $citations,
                'reasoning' => $reasoning,
            ];
        } catch (\Exception $e) {
            Log::error('LLM call exception', [
                'message' => $e->getMessage(),
            ]);
            return [
                'content' => 'Sorry, I encountered an error processing your request. Please try again.',
                'usage' => [],
            ];
        }
    }

    public function generateImage(string $prompt, string $size = '1:1'): ?string
    {
        // Menyusun prompt tambahan berdasarkan rasio
        $sizeInstruction = " The image aspect ratio must be {$size}.";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'HTTP-Referer' => config('app.url'),
                'X-Title' => config('app.name'),
            ])->post($this->baseUrl . '/chat/completions', [
                        'model' => 'google/gemini-2.5-flash-image',
                        'messages' => [
                            [
                                'role' => 'user',
                                'content' => "Generate a high-quality image for the following prompt: " . $prompt . $sizeInstruction,
                            ],
                        ],
                    ]);

            if ($response->failed()) {
                Log::error('Image generation failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return null;
            }

            // Checking image output from AI provider
            $imageUrl = $response->json('choices.0.message.images.0.image_url.url');
            if ($imageUrl) {
                return $this->downloadAndStoreImage($imageUrl);
            }

            $content = $response->json('choices.0.message.content');

            // Fallback: Mendapatkan URL dari teks atau markdown
            if ($content && preg_match('/(https?:\/\/[^\s\)]+\.(?:png|jpg|jpeg|gif|webp|svg)[^\s\)]*)/i', $content, $matches)) {
                return $this->downloadAndStoreImage($matches[1]);
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Image generation exception', [
                'message' => $e->getMessage(),
            ]);
            return null;
        }
    }

    private function downloadAndStoreImage(string $url): ?string
    {
        try {
            $filename = null;

            // Handle base64 data URLs (e.g., "data:image/png;base64,...")
            if (str_starts_with($url, 'data:image/')) {
                [$meta, $data] = explode(',', $url, 2);
                preg_match('/data:image\/(\w+);/', $meta, $matches);
                $extension = $matches[1] ?? 'png';
                $imageData = base64_decode($data);
                $filename = 'ai-images/' . uniqid('ai_', true) . '.' . $extension;
                \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $imageData);
            } else {
                // Handle regular http/https URLs
                $response = Http::timeout(30)->get($url);

                if ($response->failed()) {
                    Log::error('Failed to download image from provider', [
                        'url' => $url,
                        'status' => $response->status(),
                    ]);
                    return null;
                }

                $extension = 'png';
                $contentType = $response->header('Content-Type');
                if ($contentType) {
                    // Strip charset and other parameters: "image/png; charset=..." => "png"
                    preg_match('/image\/(\w+)/', $contentType, $matches);
                    $extension = $matches[1] ?? 'png';
                }

                $filename = 'ai-images/' . uniqid('ai_', true) . '.' . $extension;
                \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $response->body());
            }

            // Return a full web-accessible URL
            return \Illuminate\Support\Facades\Storage::disk('public')->url($filename);
        } catch (\Exception $e) {
            Log::error('Exception while storing image locally', [
                'message' => $e->getMessage(),
                'url' => substr($url, 0, 100),
            ]);
            return null;
        }
    }

    public function generateEmbedding(string $text): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'HTTP-Referer' => config('app.url'),
        ])->post($this->baseUrl . '/embeddings', [
                    'model' => 'openai/text-embedding-3-small',
                    'input' => $text,
                ]);

        if ($response->failed()) {
            throw new \RuntimeException('Failed to generate embedding: ' . $response->body());
        }

        return $response->json('data.0.embedding');
    }

    public function getKeyInfo(): ?array
    {
        return \Illuminate\Support\Facades\Cache::remember('openrouter_key_info', 600, function () {
            try {
                // Use management key if available, otherwise use standard key
                $key = $this->managementKey ?? $this->apiKey;
                
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $key,
                ])->get('https://openrouter.ai/api/v1/key');

                if ($response->failed()) {
                    Log::error('AI Provider key info failed', [
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);
                    return null;
                }

                return $response->json('data');
            } catch (\Exception $e) {
                Log::error('AI Provider key info exception', ['message' => $e->getMessage()]);
                return null;
            }
        });
    }

    public function getProfitMultiplier(): float
    {
        return (float) config('services.openrouter.profit_multiplier', 2.0);
    }

    public function getExchangeRate(): float
    {
        try {
            // Default rate fallback (avg 2024-2025)
            $defaultRate = 16000.0;

            $response = Http::timeout(5)->get('https://api.budjet.org/fiat/USD/IDR');

            if ($response->failed()) {
                Log::warning('Exchange rate API failed, using default rate', [
                    'status' => $response->status(),
                ]);
                return $defaultRate;
            }

            return (float) ($response->json('conversion_rate') ?? $defaultRate);
        } catch (\Exception $e) {
            Log::error('Exchange rate exception', ['message' => $e->getMessage()]);
            return 16000.0;
        }
    }

    /**
     * Get user activity logs from AI provider (last 30 days)
     * Requires Management Key
     */
    public function getUserActivity(?string $date = null): array
    {
        // Check if management key is configured
        if (empty($this->managementKey)) {
            return ['data' => [], 'error' => 'Management key not configured. Add OPENROUTER_MANAGEMENT_KEY to your .env file'];
        }

        try {
            $url = 'https://openrouter.ai/api/v1/activity';

            $params = [];
            if ($date) {
                $params['date'] = $date;
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->managementKey,
                'HTTP-Referer' => config('app.url'),
                'X-Title' => config('app.name'),
                'Content-Type' => 'application/json',
            ])->get($url, $params);

            if ($response->failed()) {
                $errorMessage = 'Activity API failed';
                $errorDetails = $response->json('error.message') ?? $response->body();

                Log::error($errorMessage, [
                    'status' => $response->status(),
                    'body' => $errorDetails,
                    'url' => $url,
                    'params' => $params,
                ]);

                // Check for specific error types
                if ($response->status() === 401) {
                    return ['data' => [], 'error' => 'Unauthorized: Invalid or expired Management API key'];
                } elseif ($response->status() === 403) {
                    return ['data' => [], 'error' => 'Forbidden: API key lacks required permissions (Management key required)'];
                } elseif ($response->status() === 404) {
                    return ['data' => [], 'error' => 'Activity endpoint not available'];
                }

                return ['data' => [], 'error' => 'Failed to fetch activity data: ' . $errorDetails];
            }

            $result = $response->json();

            // Log successful response for debugging
            Log::info('Activity API success', [
                'data_count' => count($result['data'] ?? []),
            ]);
            
            return $result;
        } catch (\Exception $e) {
            Log::error('OpenRouter activity exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return ['data' => [], 'error' => 'Exception: ' . $e->getMessage()];
        }
    }

    /**
     * Get usage statistics grouped by model
     */
    public function getModelUsageStats(int $days = 30): array
    {
        $activity = $this->getUserActivity();
        
        if (isset($activity['error']) || empty($activity['data'])) {
            return [
                'models' => [],
                'totalUsage' => 0,
                'totalRequests' => 0,
                'totalTokens' => 0,
            ];
        }

        $modelStats = [];
        $totalUsage = 0;
        $totalRequests = 0;
        $totalTokens = 0;

        foreach ($activity['data'] as $item) {
            $modelId = $item['model'] ?? 'unknown';
            
            if (!isset($modelStats[$modelId])) {
                $modelStats[$modelId] = [
                    'model' => $modelId,
                    'provider' => $item['provider_name'] ?? 'Unknown',
                    'usage' => 0,
                    'requests' => 0,
                    'prompt_tokens' => 0,
                    'completion_tokens' => 0,
                    'reasoning_tokens' => 0,
                    'total_tokens' => 0,
                ];
            }

            $modelStats[$modelId]['usage'] += (float) ($item['usage'] ?? 0);
            $modelStats[$modelId]['requests'] += (int) ($item['requests'] ?? 0);
            $modelStats[$modelId]['prompt_tokens'] += (int) ($item['prompt_tokens'] ?? 0);
            $modelStats[$modelId]['completion_tokens'] += (int) ($item['completion_tokens'] ?? 0);
            $modelStats[$modelId]['reasoning_tokens'] += (int) ($item['reasoning_tokens'] ?? 0);
            $modelStats[$modelId]['total_tokens'] += (int) (($item['prompt_tokens'] ?? 0) + ($item['completion_tokens'] ?? 0));

            $totalUsage += (float) ($item['usage'] ?? 0);
            $totalRequests += (int) ($item['requests'] ?? 0);
            $totalTokens += (int) (($item['prompt_tokens'] ?? 0) + ($item['completion_tokens'] ?? 0));
        }

        // Sort by usage (highest first)
        usort($modelStats, function ($a, $b) {
            return $b['usage'] <=> $a['usage'];
        });

        return [
            'models' => $modelStats,
            'totalUsage' => $totalUsage,
            'totalRequests' => $totalRequests,
            'totalTokens' => $totalTokens,
        ];
    }

    /**
     * Get daily usage for chart
     */
    public function getDailyUsage(): array
    {
        $activity = $this->getUserActivity();
        
        if (isset($activity['error']) || empty($activity['data'])) {
            return [];
        }

        $dailyStats = [];

        foreach ($activity['data'] as $item) {
            $date = $item['date'] ?? null;
            if (!$date) continue;

            if (!isset($dailyStats[$date])) {
                $dailyStats[$date] = [
                    'date' => $date,
                    'usage' => 0,
                    'requests' => 0,
                    'tokens' => 0,
                ];
            }

            $dailyStats[$date]['usage'] += (float) ($item['usage'] ?? 0);
            $dailyStats[$date]['requests'] += (int) ($item['requests'] ?? 0);
            $dailyStats[$date]['tokens'] += (int) (($item['prompt_tokens'] ?? 0) + ($item['completion_tokens'] ?? 0));
        }

        // Sort by date
        ksort($dailyStats);

        return array_values($dailyStats);
    }
}
