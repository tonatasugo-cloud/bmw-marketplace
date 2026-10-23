<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $seller = Auth::user()->seller;

        if (!$seller) {
            return redirect()->route('seller.profile.edit');
        }

        $conversations = Conversation::where('seller_id', $seller->id)
            ->with(['buyer', 'car', 'lastMessage'])
            ->orderByDesc('last_message_at')
            ->paginate(20);

        return view('seller.messages.index', compact('conversations'));
    }
}