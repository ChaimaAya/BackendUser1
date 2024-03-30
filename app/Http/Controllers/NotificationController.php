<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function likedNotifications(Request $request)
    {
        $user = $request->user();

        // Récupérer les IDs des publications de l'utilisateur authentifié
        $userPostIds = $user->publications()->pluck('id');

        // Récupérer les IDs des notifications associées aux publications de l'utilisateur authentifié
        $likedNotificationIds = DB::table('notifications') 
                                    ->join('users', 'notifications.notifiable_id', '=', 'users.id')
                                    ->whereIn('data->publication', $userPostIds) 
                                    ->where('notifications.type', 'App\Notifications\LikedDBNotify') 
                                    ->where('notifications.notifiable_id', $user->id)
                                    ->whereNull('notifications.read_at')
                                    ->pluck('notifications.id');

        // Récupérer les détails des notifications
        $likedNotifications = DB::table('notifications')
                                    ->whereIn('id', $likedNotificationIds)
                                    ->select('id', 'data', 'created_at')
                                    ->get();

        return response()->json(['likedNotifications' => $likedNotifications], 200);
    }
    public function markAsRead($id)
    {
        if ($id) {
            $notification = auth()->user()->notifications->find( $id); 
            if ($notification) {
                $notification->markAsRead();
                return response()->json(['message' => 'Notification marked as read successfully'], 200);
            } else {
                return response()->json(['error' => 'Notification not found'], 404);
            }
        } else {
            return response()->json(['error' => 'Invalid notification ID'], 400);
        }
    }
    public function markAsReadAll()
    {
        $userUnreadNotifications = Auth::user()->unreadNotifications;

        if($userUnreadNotifications->isNotEmpty()) { 
            $userUnreadNotifications->markAsRead();
            return response()->json(['message' => 'All unread notifications marked as read successfully'], 200);
        } else {
            return response()->json(['error' => 'No unread notifications found'], 200); 
        }
    }
    public function countNotifications(){
        if($count=Auth::user()->unreadNotifications->count()){
            return response()->json(['countNotifications'=>$count]);


        }
        else {
            return response()->json([['countNotifications'=>$count]], 200); 


        }


    }

}
