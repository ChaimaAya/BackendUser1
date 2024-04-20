<?php

namespace App\Notifications;

use App\Models\Follower;
use App\Models\Messages;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class MessageDBNotify  extends Notification
{
    use Queueable;
    private $message,$operation;

    /**
     * Create a new notification instance.
     */
    public function __construct(Messages $message,$operation)
    {
        $this->message=$message;
        $this->operation= $operation;

    }


    public function via( $notifiable)
    {
        return ['database'];
    }

    public function toDataBase($notifiable)
    {
        $message='';
        if ($this->operation =='message'){
            $message ="a envoyer un message";
        }



        return [
            "message"=> $this->message->from_user,
            "title"=> $message,
            "description"=> $this->message->content,
            'user'=>Auth::user()->name,
            'image'=>Auth::user()->image,
            'operation' => $this->operation,


        ];
    }
   
}
