<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Display a listing of messages for the authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();
        $query = Message::with(['sender', 'receiver']);

        // Get messages where user is sender or receiver
        $query->where(function ($q) use ($user) {
            $q->where('sender_id', $user->id)
              ->orWhere('receiver_id', $user->id);
        });

        // Filter by conversation with specific user
        if ($request->has('with_user')) {
            $query->betweenUsers($user->id, $request->with_user);
        }

        // Filter by read status
        if ($request->has('is_read')) {
            if ($request->boolean('is_read')) {
                $query->read();
            } else {
                $query->unread();
            }
        }

        // Filter by sent/received
        if ($request->has('type')) {
            if ($request->type === 'sent') {
                $query->bySender($user->id);
            } elseif ($request->type === 'received') {
                $query->byReceiver($user->id);
            }
        }

        // Filter by recent messages
        if ($request->has('recent_days')) {
            $query->recent($request->recent_days);
        }

        $messages = $query->orderBy('sent_at', 'desc')->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $messages
        ]);
    }

    /**
     * Store a newly created message.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string|max:1000'
        ]);

        // Prevent sending message to self
        if ($validated['receiver_id'] == Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot send message to yourself'
            ], 422);
        }

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $validated['receiver_id'],
            'content' => $validated['content'],
            'sent_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully',
            'data' => $message->load(['sender', 'receiver'])
        ], 201);
    }

    /**
     * Display the specified message.
     */
    public function show(Message $message): JsonResponse
    {
        $user = Auth::user();

        // Check if user is sender or receiver
        if ($message->sender_id !== $user->id && $message->receiver_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to message'
            ], 403);
        }

        // Mark as read if user is receiver
        if ($message->receiver_id === $user->id && !$message->is_read) {
            $message->markAsRead();
        }

        return response()->json([
            'success' => true,
            'data' => $message->load(['sender', 'receiver'])
        ]);
    }

    /**
     * Update the specified message.
     */
    public function update(Request $request, Message $message): JsonResponse
    {
        $user = Auth::user();

        // Only sender can edit message
        if ($message->sender_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Only sender can edit message'
            ], 403);
        }

        $validated = $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        $message->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Message updated successfully',
            'data' => $message->load(['sender', 'receiver'])
        ]);
    }

    /**
     * Remove the specified message.
     */
    public function destroy(Message $message): JsonResponse
    {
        $user = Auth::user();

        // Only sender can delete message
        if ($message->sender_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Only sender can delete message'
            ], 403);
        }

        $message->delete();

        return response()->json([
            'success' => true,
            'message' => 'Message deleted successfully'
        ]);
    }

    /**
     * Get conversation between two users.
     */
    public function getConversation(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'with_user' => 'required|exists:users,id'
        ]);

        $user = Auth::user();
        $otherUserId = $validated['with_user'];

        // Prevent getting conversation with self
        if ($otherUserId == $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot get conversation with yourself'
            ], 422);
        }

        $messages = Message::betweenUsers($user->id, $otherUserId)
            ->with(['sender', 'receiver'])
            ->orderBy('sent_at', 'asc')
            ->paginate(50);

        return response()->json([
            'success' => true,
            'data' => $messages
        ]);
    }

    /**
     * Mark message as read.
     */
    public function markAsRead(Message $message): JsonResponse
    {
        $user = Auth::user();

        // Only receiver can mark as read
        if ($message->receiver_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Only receiver can mark message as read'
            ], 403);
        }

        $message->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Message marked as read'
        ]);
    }

    /**
     * Mark all messages from a user as read.
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'from_user' => 'required|exists:users,id'
        ]);

        $user = Auth::id();
        $fromUserId = $validated['from_user'];

        Message::where('sender_id', $fromUserId)
            ->where('receiver_id', $user)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'All messages marked as read'
        ]);
    }

    /**
     * Get unread message count.
     */
    public function getUnreadCount(): JsonResponse
    {
        $user = Auth::id();
        
        $count = Message::where('receiver_id', $user)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'success' => true,
            'data' => ['unread_count' => $count]
        ]);
    }

    /**
     * Get recent conversations.
     */
    public function getRecentConversations(): JsonResponse
    {
        $user = Auth::id();

        // Get recent conversations with message preview
        $conversations = Message::select('sender_id', 'receiver_id', 'content', 'sent_at', 'is_read')
            ->where(function ($query) use ($user) {
                $query->where('sender_id', $user)
                      ->orWhere('receiver_id', $user);
            })
            ->whereIn('id', function ($query) use ($user) {
                $query->selectRaw('MAX(id)')
                      ->from('messages')
                      ->where(function ($q) use ($user) {
                          $q->where('sender_id', $user)
                            ->orWhere('receiver_id', $user);
                      })
                      ->groupByRaw('CASE 
                          WHEN sender_id = ? THEN receiver_id 
                          ELSE sender_id 
                      END', [$user]);
            })
            ->with(['sender', 'receiver'])
            ->orderBy('sent_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $conversations
        ]);
    }

    /**
     * Search messages by content.
     */
    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'query' => 'required|string|min:2|max:100'
        ]);

        $user = Auth::id();
        $searchQuery = $validated['query'];

        $messages = Message::where(function ($q) use ($user) {
            $q->where('sender_id', $user)
              ->orWhere('receiver_id', $user);
        })
        ->where('content', 'like', '%' . $searchQuery . '%')
        ->with(['sender', 'receiver'])
        ->orderBy('sent_at', 'desc')
        ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $messages
        ]);
    }
}
