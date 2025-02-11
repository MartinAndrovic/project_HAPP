<?php

namespace App\Notifications;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;


class NewMatchNotification extends Notification
{
    use Queueable;
    public $match_id;

    public function __construct($match)
    {
        $this->match_id = $match;
    }


    public function via(object $notifiable): array
    {
        return ['database'];
    }


    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'you have a new match'
        ];
    }
}
