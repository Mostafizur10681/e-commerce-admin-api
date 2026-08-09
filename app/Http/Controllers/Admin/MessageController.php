<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('subject', 'like', "%{$s}%")
                  ->orWhere('message', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $messages = $query->latest()->paginate(15)->withQueryString();

        $unreadCount = ContactMessage::where('status', 'Unread')->count();
        $totalCount = ContactMessage::count();

        // Selected active message to view in drawer
        $activeMessage = null;
        if ($request->filled('id')) {
            $activeMessage = ContactMessage::find($request->id);
            if ($activeMessage && $activeMessage->status === 'Unread') {
                $activeMessage->update(['status' => 'Read']);
            }
        }

        return view('admin.messages.index', compact('messages', 'unreadCount', 'totalCount', 'activeMessage'));
    }

    public function toggleStatus($id)
    {
        $msg = ContactMessage::findOrFail($id);
        $msg->status = $msg->status === 'Read' ? 'Unread' : 'Read';
        $msg->save();

        return back()->with('success', 'Message marked as ' . $msg->status);
    }

    public function markAllAsRead()
    {
        ContactMessage::where('status', 'Unread')->update(['status' => 'Read']);
        return back()->with('success', 'All messages marked as read.');
    }

    public function destroy($id)
    {
        $msg = ContactMessage::findOrFail($id);
        $msg->delete();

        return redirect()->route('admin.messages.index')->with('success', 'Message deleted successfully!');
    }
}
