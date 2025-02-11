<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getNotifications()
    {
        $notifications = auth()->user()->notifications;
        foreach ($notifications as $notification) {
            $notification->type = str_replace('App\\Notifications\\', "", $notification->type);
        }
        return $notifications;
    }
}
