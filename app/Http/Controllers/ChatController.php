<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $conversations = Conversation::where('buyer_id', $userId)
            ->orWhereHas('seller', fn($q) => $q->where('user_id', $userId))
            ->with(['buyer', 'seller.user', 'car', 'lastMessage'])
            ->orderByDesc('last_message_at')
            ->paginate(20);

        return view('chat.index', compact('conversations'));
    }

    public function show(Conversation $conversation)
    {
        $this->authorizeConversation($conversation);

        $conversation->load(['buyer', 'seller.user', 'car.primaryImage']);

        // Mark messages as read
        $conversation->messages()
            ->where('sender_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        $messages = $conversation->messages()
            ->with('sender')
            ->oldest()
            ->get();

        return view('chat.show', compact('conversation', 'messages'));
    }

    public function start(Request $request, Car $car)
    {
        $seller = $car->seller;

        if (!$seller) {
            return back()->withErrors(['msg' => 'Seller tidak ditemukan.']);
        }

        // Buyer tidak bisa chat seller sendiri
        if ($seller->user_id === Auth::id()) {
            return redirect()->route('seller.messages.index')
                ->with('warning', 'Ini mobil Anda sendiri.');
        }

        $conversation = Conversation::firstOrCreate(
            [
                'buyer_id'  => Auth::id(),
                'seller_id' => $seller->id,
                'car_id'    => $car->id,
            ],
            ['last_message_at' => now()]
        );

        if ($request->filled('message')) {
            Message::create([
                'conversation_id' => $conversation->id,
                'sender_id'       => Auth::id(),
                'body'            => $request->message,
            ]);
            $conversation->update(['last_message_at' => now()]);
        }

        return redirect()->route('chat.show', $conversation);
    }

    public function store(Request $request, Conversation $conversation)
    {
        $this->authorizeConversation($conversation);

        $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id'       => Auth::id(),
            'body'            => $request->body,
        ]);

        $conversation->update(['last_message_at' => now()]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message->load('sender'),
            ]);
        }

        return back();
    }

    private function authorizeConversation(Conversation $conversation)
    {
        $userId = Auth::id();
        $sellerUserId = $conversation->seller->user_id;

        if ($conversation->buyer_id !== $userId && $sellerUserId !== $userId) {
            abort(403, 'Akses chat ditolak.');
        }
    }
}