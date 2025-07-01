<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get notifications for current user (global + user-specific)
        $notifications = Notification::forUser($user->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get unread count
        $unreadCount = Notification::forUser($user->id)
            ->unread()
            ->count();

        // Get users for notification targeting (super admin only)
        $users = collect();
        if ($user->hasRole(['super_admin'])) {
            $users = User::select('id', 'full_name', 'username')
                ->orderBy('full_name')
                ->get();
        }

        // Auto-generate low stock notifications
        $this->generateLowStockNotifications();

        return view('notifications.index', compact('notifications', 'unreadCount', 'users'));
    }

    public function markAsRead(Request $request)
    {
        $notification = Notification::findOrFail($request->notification_id);
        
        $notification->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead()
    {
        $user = Auth::user();
        
        $unreadCount = Notification::forUser($user->id)->unread()->count();
        
        Notification::forUser($user->id)
            ->unread()
            ->update(['is_read' => true]);

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,success,warning,danger',
            'user_id' => 'nullable|exists:users,id'
        ]);

        $notification = Notification::create([
            'user_id' => $request->user_id ?: null,
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type,
            'is_read' => false
        ]);

        return redirect()->back()->with('success', 'Notification created successfully.');
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();

        return redirect()->back()->with('success', 'Notification deleted.');
    }

    private function generateLowStockNotifications()
    {
        $lowStockCount = DB::table('items')->where('quantity', '<=', 10)->count();

        if ($lowStockCount > 0) {
            // Check if we already have a recent low stock notification
            $recentAlert = Notification::where('title', 'LIKE', '%Low Stock Alert%')
                ->where('created_at', '>=', now()->subDay())
                ->count();

            if ($recentAlert == 0) {
                Notification::create([
                    'title' => 'Low Stock Alert',
                    'message' => "There are {$lowStockCount} items with low stock levels that need attention.",
                    'type' => 'warning',
                    'is_read' => false
                ]);
            }
        }
    }
}