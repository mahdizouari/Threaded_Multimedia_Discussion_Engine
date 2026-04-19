<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $search = $request->query('u_search');

        // Get unique users with whom we have messaged
        $conversations = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['sender', 'receiver'])
            ->latest()
            ->get()
            ->map(function ($msg) use ($userId) {
                return $msg->sender_id === $userId ? $msg->receiver : $msg->sender;
            })
            ->unique('id');

        // If searching and no existing conversation, find user
        if ($search) {
            $searchResults = User::where('id', '!=', $userId)
                ->where(function($q) use ($search) {
                    $q->where('username', 'LIKE', "%{$search}%")
                      ->orWhere('name', 'LIKE', "%{$search}%");
                })
                ->get();
            
            // Merge search results into conversations for display
            $conversations = $conversations->concat($searchResults)->unique('id');
        }

        return view('user.messages.index', compact('conversations', 'search'));
    }

    public function show(User $user)
    {
        $userId = Auth::id();
        
        $messages = Message::where(function($q) use ($userId, $user) {
                $q->where('sender_id', $userId)->where('receiver_id', $user->id);
            })->orWhere(function($q) use ($userId, $user) {
                $q->where('sender_id', $user->id)->where('receiver_id', $userId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('user.messages.show', compact('user', 'messages'));
    }

    public function fetchMessages(User $user)
    {
        $userId = Auth::id();
        
        $messages = Message::where(function($q) use ($userId, $user) {
                $q->where('sender_id', $userId)->where('receiver_id', $user->id);
            })->orWhere(function($q) use ($userId, $user) {
                $q->where('sender_id', $user->id)->where('receiver_id', $userId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'messages' => $messages,
            'current_user_id' => $userId
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'body' => 'nullable|string',
            'attachment' => 'nullable|file|max:10240', // 10MB max
        ]);

        $filePath = null;
        $fileType = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filePath = $file->store('attachments', 'public');
            $fileType = $file->getMimeType();
        }

        if (!$validated['body'] && !$filePath) {
            return back()->withErrors(['body' => 'Message or attachment required.']);
        }

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $validated['receiver_id'],
            'body' => $validated['body'],
            'file_path' => $filePath,
            'file_type' => $fileType,
        ]);

        return back()->with('success', 'Message sent successfully.');
    }
}
