<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications for the authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();
        $query = Notification::byUser($user->id)->ordered();

        // Filter by read status
        if ($request->has('is_read')) {
            if ($request->boolean('is_read')) {
                $query->read();
            } else {
                $query->unread();
            }
        }

        // Filter by type
        if ($request->has('type')) {
            $query->byType($request->type);
        }

        // Filter by recent notifications
        if ($request->has('recent_days')) {
            $query->recent($request->recent_days);
        }

        $notifications = $query->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $notifications
        ]);
    }

    /**
     * Store a newly created notification.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'message_id' => 'nullable|exists:messages,id',
            'type' => 'required|string|in:message,order,promotion,system',
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:1000',
            'data' => 'nullable|array'
        ]);

        $notification = Notification::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Notification created successfully',
            'data' => $notification->load(['user', 'message'])
        ], 201);
    }

    /**
     * Display the specified notification.
     */
    public function show(Notification $notification): JsonResponse
    {
        $user = Auth::user();

        // Check if user owns this notification
        if ($notification->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to notification'
            ], 403);
        }

        // Mark as read if not already read
        if (!$notification->is_read) {
            $notification->markAsRead();
        }

        return response()->json([
            'success' => true,
            'data' => $notification->load(['user', 'message'])
        ]);
    }

    /**
     * Update the specified notification.
     */
    public function update(Request $request, Notification $notification): JsonResponse
    {
        $user = Auth::user();

        // Check if user owns this notification
        if ($notification->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to notification'
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'body' => 'sometimes|string|max:1000',
            'data' => 'sometimes|array'
        ]);

        $notification->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Notification updated successfully',
            'data' => $notification->load(['user', 'message'])
        ]);
    }

    /**
     * Remove the specified notification.
     */
    public function destroy(Notification $notification): JsonResponse
    {
        $user = Auth::user();

        // Check if user owns this notification
        if ($notification->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to notification'
            ], 403);
        }

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted successfully'
        ]);
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead(Notification $notification): JsonResponse
    {
        $user = Auth::user();

        // Check if user owns this notification
        if ($notification->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to notification'
            ], 403);
        }

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    }

    /**
     * Mark notification as unread.
     */
    public function markAsUnread(Notification $notification): JsonResponse
    {
        $user = Auth::user();

        // Check if user owns this notification
        if ($notification->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to notification'
            ], 403);
        }

        $notification->markAsUnread();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as unread'
        ]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(): JsonResponse
    {
        $user = Auth::id();

        Notification::byUser($user)
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }

    /**
     * Mark all notifications of a specific type as read.
     */
    public function markAllByTypeAsRead(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type' => 'required|string|in:message,order,promotion,system'
        ]);

        $user = Auth::id();

        Notification::byUser($user)
            ->byType($validated['type'])
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);

        return response()->json([
            'success' => true,
            'message' => "All {$validated['type']} notifications marked as read"
        ]);
    }

    /**
     * Get unread notification count.
     */
    public function getUnreadCount(): JsonResponse
    {
        $user = Auth::id();
        
        $count = Notification::byUser($user)->unread()->count();

        return response()->json([
            'success' => true,
            'data' => ['unread_count' => $count]
        ]);
    }

    /**
     * Get unread count by type.
     */
    public function getUnreadCountByType(): JsonResponse
    {
        $user = Auth::id();
        
        $counts = [
            'message' => Notification::byUser($user)->byType('message')->unread()->count(),
            'order' => Notification::byUser($user)->byType('order')->unread()->count(),
            'promotion' => Notification::byUser($user)->byType('promotion')->unread()->count(),
            'system' => Notification::byUser($user)->byType('system')->unread()->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $counts
        ]);
    }

    /**
     * Get recent notifications.
     */
    public function getRecent(Request $request): JsonResponse
    {
        $user = Auth::user();
        $limit = $request->get('limit', 10);

        $notifications = Notification::byUser($user->id)
            ->ordered()
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $notifications
        ]);
    }

    /**
     * Delete all read notifications.
     */
    public function deleteRead(): JsonResponse
    {
        $user = Auth::id();

        $deletedCount = Notification::byUser($user)
            ->read()
            ->delete();

        return response()->json([
            'success' => true,
            'message' => "{$deletedCount} read notifications deleted successfully"
        ]);
    }

    /**
     * Delete all notifications of a specific type.
     */
    public function deleteByType(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type' => 'required|string|in:message,order,promotion,system'
        ]);

        $user = Auth::id();

        $deletedCount = Notification::byUser($user)
            ->byType($validated['type'])
            ->delete();

        return response()->json([
            'success' => true,
            'message' => "{$deletedCount} {$validated['type']} notifications deleted successfully"
        ]);
    }

    /**
     * Search notifications by title or body.
     */
    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'query' => 'required|string|min:2|max:100'
        ]);

        $user = Auth::id();
        $searchQuery = $validated['query'];

        $notifications = Notification::byUser($user)
            ->where(function ($query) use ($searchQuery) {
                $query->where('title', 'like', '%' . $searchQuery . '%')
                      ->orWhere('body', 'like', '%' . $searchQuery . '%');
            })
            ->ordered()
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $notifications
        ]);
    }
}
