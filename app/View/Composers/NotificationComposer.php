<?php

namespace App\View\Composers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Get latest 10 notifications
            $notifications = Notification::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
            
            // Get unread count
            $unreadCount = Notification::where('user_id', $user->id)
                ->where('is_read', false)
                ->count();
            
            $view->with([
                'notifications' => $notifications,
                'unreadCount' => $unreadCount
            ]);
        } else {
            $view->with([
                'notifications' => collect(),
                'unreadCount' => 0
            ]);
        }
    }
}
