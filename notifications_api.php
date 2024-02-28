<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\notifications;
use Illuminate\Http\Request;

class notifications_api extends Controller
{


    public function index(){
        $user = auth()->user();
        $notifications = notifications::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        return response()->json($notifications);
    }

    public function markAsRead(){
        $user = auth()->user();
        $user->unreadNotifications->markAsRead();
        return response()->json(['message' => 'All notifications marked as read']);
    }

    public function markAsReadOne($id){
        $user = auth()->user();
        $user->unreadNotifications->where('id', $id)->markAsRead();
        return response()->json(['message' => 'Notification marked as read']);
    }

    public function destroy($id){
        $user = auth()->user();
        $user->notifications->where('id', $id)->delete();
        return response()->json(['message' => 'Notification deleted']);
    }
}
