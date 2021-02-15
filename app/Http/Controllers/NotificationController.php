<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Notification;
use App\Notifications\NewSharedProject;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function markRead() {
        //mark notifications as read for the logged-in user
        foreach(Auth::user()->unreadNotifications as $notification) {

            $notification -> markAsRead();
        }
        return redirect()->route('home');
    }
}
