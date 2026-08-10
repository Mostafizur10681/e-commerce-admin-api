<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        // Group chats by session_id or user_id
        $sessions = ChatMessage::with('user')
            ->select('session_id', 'user_id')
            ->selectRaw('MAX(created_at) as last_message_at, COUNT(*) as total_messages')
            ->groupBy('session_id', 'user_id')
            ->orderByDesc('last_message_at')
            ->get();

        $activeSessionId = $request->get('session_id', $sessions->first()->session_id ?? null);
        
        $messages = [];
        if ($activeSessionId) {
            $messages = ChatMessage::where('session_id', $activeSessionId)->oldest()->get();
            ChatMessage::where('session_id', $activeSessionId)->where('sender', 'user')->update(['is_read' => true]);
        }

        return view('admin.chats.index', compact('sessions', 'activeSessionId', 'messages'));
    }

    public function reply(Request $request)
    {
        $data = $request->validate([
            'session_id' => 'required|string',
            'message' => 'required|string',
        ]);

        $admin = Auth::user();
        $userId = ChatMessage::where('session_id', $data['session_id'])->whereNotNull('user_id')->value('user_id');

        ChatMessage::create([
            'session_id' => $data['session_id'],
            'user_id' => $userId,
            'admin_id' => $admin ? $admin->id : null,
            'sender' => 'admin',
            'message' => $data['message'],
            'is_read' => true,
        ]);

        return redirect()->route('admin.chats.index', ['session_id' => $data['session_id']])->with('success', 'Reply sent!');
    }
}
