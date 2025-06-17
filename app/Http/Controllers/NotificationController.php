<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the user's notifications.
     */
    public function index()
    {
        $user = Auth::user();
        $notifications = Notification::where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(15);
        
        return view('notifications.index', compact('notifications'));
    }
    
    /**
     * Mark a notification as read.
     */
    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }
        
        $notification->markAsRead();
        
        if ($notification->link) {
            return redirect($notification->link);
        }
        
        return back();
    }
    
    /**
     * Mark all user's notifications as read.
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        Notification::where('user_id', $user->id)->whereNull('read_at')->update(['read_at' => now()]);
        
        return back()->with('success', 'All notifications marked as read.');
    }
    
    /**
     * Get the current user's unread notifications (for AJAX).
     */
    public function getUnread()
    {
        $user = Auth::user();
        $notifications = Notification::where('user_id', $user->id)->whereNull('read_at')->orderBy('created_at', 'desc')->take(5)->get();
        $unreadCount = Notification::where('user_id', $user->id)->whereNull('read_at')->count();
        
        return response()->json([
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ]);
    }
}