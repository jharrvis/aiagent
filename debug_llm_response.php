<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(Illuminate\Http\Request::capture());

use App\Services\LLMService;
use App\Models\Agent;
use Illuminate\Support\Collection;

$llm = new LLMService();
$agent = Agent::first();

echo "Testing Chat Completion Structure...\n";
$res = $llm->chat($agent, collect([]), "Say hello");

echo "Response Body:\n";
print_r($res);
