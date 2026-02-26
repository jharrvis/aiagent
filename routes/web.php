<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('marketplace');
});

Route::get('/asisten-ceo', function () {
    return view('marketplace');
})->name('marketplace');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/agents/{agent}', function (\App\Models\Agent $agent) {
        $recentConversations = \App\Models\Conversation::with([
            'messages' => function ($query) {
                $query->oldest()->limit(1);
            }
        ])
            ->where('agent_id', $agent->id)
            ->where('user_id', auth()->id())
            ->orderByDesc('updated_at')
            ->limit(20)
            ->get();
        return view('chat', compact('agent', 'recentConversations'));
    })->name('agents.chat');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/conversations', [ConversationController::class, 'index'])->name('conversations.index');
    Route::post('/conversations', [ConversationController::class, 'store'])->name('conversations.store');
    Route::get('/conversations/{conversation}', [ConversationController::class, 'show'])->name('conversations.show');
    Route::delete('/conversations/{conversation}', [ConversationController::class, 'destroy'])->name('conversations.destroy');
    Route::get('/conversations/{conversation}/download', [ConversationController::class, 'download'])->name('conversations.download');

    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/{message}/pdf', [MessageController::class, 'downloadPdf'])->name('messages.pdf');
    Route::get('/messages/{message}/excel', [MessageController::class, 'downloadExcel'])->name('messages.excel');

    Route::get('/gallery', [\App\Http\Controllers\GalleryController::class, 'index'])->name('gallery');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/settings', [\App\Http\Controllers\Admin\DashboardController::class, 'saveSettings'])->name('settings.save');

    Route::resource('agents', \App\Http\Controllers\Admin\AgentController::class);
    Route::post('agents/{agent}/knowledge', [\App\Http\Controllers\Admin\AgentController::class, 'uploadKnowledge'])
        ->name('agents.knowledge.upload');
    Route::post('agents/{agent}/toggle-status', [\App\Http\Controllers\Admin\AgentController::class, 'toggleStatus'])
        ->name('agents.toggle-status');

    // User Management Routes
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::post('users/{user}/toggle-status', [\App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])
        ->name('users.toggle-status');
    Route::post('users/{user}/topup', [\App\Http\Controllers\Admin\UserController::class, 'topup'])
        ->name('users.topup');

    // Analytics Routes
    Route::get('analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('analytics/data', [\App\Http\Controllers\Admin\AnalyticsController::class, 'data'])->name('analytics.data');

    // Excel Template Management
    Route::resource('excel-templates', \App\Http\Controllers\Admin\ExcelTemplateController::class);
    Route::get('excel-templates/{excelTemplate}/download', [\App\Http\Controllers\Admin\ExcelTemplateController::class, 'download'])
        ->name('excel-templates.download');
});

require __DIR__ . '/auth.php';
