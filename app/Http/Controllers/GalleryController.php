<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GalleryController extends Controller
{
    public function index()
    {
        // Ambil semua pesan bot yang mengandung URL gambar untuk user yang sedang login
        $messages = Message::whereHas('conversation', function ($query) {
            $query->where('user_id', Auth::id());
        })
            ->where('role', 'assistant')
            ->whereNotNull('metadata->image_url')
            ->latest()
            ->paginate(12);

        return view('gallery', compact('messages'));
    }
}
