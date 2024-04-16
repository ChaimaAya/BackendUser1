<?php

namespace App\Notifications;

use App\Models\Follower;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class FollowDBNotify extends Notification
{
    use Queueable;
    private $follow,$operation;

    /**
     * Create a new notification instance.
     */
    public function __construct(Follower $likes,$operation)
    {
        $this->follow=$likes;
        $this->operation= $operation;

    }


    public function via( $notifiable)
    {
        return ['database'];
    }

    public function toDataBase($notifiable)
    {
        $message='';
        if ($this->operation =='follow'){
            $message ="a abonné vous";
        }



        return [
            "follow"=> $this->follow->follower_id,
            "title"=> $message,
            'user'=>Auth::user()->name,
            'image'=>Auth::user()->image,
            'operation' => $this->operation,


        ];
    }
   
}
