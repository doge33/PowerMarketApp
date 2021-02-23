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
  public function markAsRead(Request $request) {

    $notification= auth()->user()->unreadNotifications->find($request->notificationId);

    if($notification !== null) {
      $notification->markAsRead();
    }

    return response()->json([
      'message' => 'Notification marked as Read.'
    ], 200);
  }
}
