<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$r = Illuminate\Support\Facades\Http::withHeaders([
    'Authorization' => 'Bearer ' . config('services.openrouter.api_key'),
    'HTTP-Referer' => config('app.url'),
])->post(config('services.openrouter.base_url') . '/chat/completions', [
            'model' => 'google/gemini-2.5-flash-image',
            'messages' => [['role' => 'user', 'content' => 'A red apple']]
        ]);
file_put_contents('test_dalle_response.json', json_encode($r->json(), JSON_PRETTY_PRINT));
echo "Saved to test_dalle_response.json\n";
