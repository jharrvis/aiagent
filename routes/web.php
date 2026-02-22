<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('marketplace');
});

Route::get('/marketplace', function () {
    return view('marketplace');
})->name('marketplace');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/agents/{agent}', function (\App\Models\Agent $agent) {
        return view('chat', compact('agent'));
    })->name('agents.chat');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/conversations', [ConversationController::class, 'index'])->name('conversations.index');
    Route::post('/conversations', [ConversationController::class, 'store'])->name('conversations.store');
    Route::get('/conversations/{conversation}', [ConversationController::class, 'show'])->name('conversations.show');

    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/{message}/pdf', [MessageController::class, 'downloadPdf'])->name('messages.pdf');

    Route::get('/gallery', [\App\Http\Controllers\GalleryController::class, 'index'])->name('gallery');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('agents', \App\Http\Controllers\Admin\AgentController::class);
    Route::post('agents/{agent}/knowledge', [\App\Http\Controllers\Admin\AgentController::class, 'uploadKnowledge'])
        ->name('agents.knowledge.upload');
});

require __DIR__ . '/auth.php';
