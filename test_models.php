<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$response = Illuminate\Support\Facades\Http::get('https://openrouter.ai/api/v1/models');
$models = $response->json('data') ?? [];
$imageModels = [];
foreach ($models as $model) {
    $outputs = $model['architecture']['modality'] ?? ''; // OpenRouter often uses modality string
    if (stripos($outputs, 'image') !== false || stripos($model['description'] ?? '', 'image generation') !== false) {
        $imageModels[] = $model['id'];
    }
}
echo json_encode(['models' => array_slice($imageModels, 0, 20)], JSON_PRETTY_PRINT);
