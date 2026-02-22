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

    public function __construct()
    {
        $this->baseUrl = config('services.openrouter.base_url');
        $this->apiKey = config('services.openrouter.api_key');
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
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'HTTP-Referer' => config('app.url'),
                'X-Title' => config('app.name'),
            ])->post($this->baseUrl . '/chat/completions', [
                        'model' => $agent->openrouter_model_id,
                        'messages' => $chatMessages,
                        'temperature' => (float) $agent->temperature,
                    ]);

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

            return [
                'content' => $response->json('choices.0.message.content'),
                'usage' => $response->json('usage'),
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

            // Memeriksa output gambar dari OpenRouter format
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
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                ])->get('https://openrouter.ai/api/v1/key');

                if ($response->failed()) {
                    Log::error('OpenRouter key info failed', [
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);
                    return null;
                }

                return $response->json('data');
            } catch (\Exception $e) {
                Log::error('OpenRouter key info exception', ['message' => $e->getMessage()]);
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
}
