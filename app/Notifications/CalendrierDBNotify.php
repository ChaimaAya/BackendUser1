<?php

namespace App\Notifications;

use App\Models\Follower;
use App\Models\Messages;
use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class CalendrierDBNotify  extends Notification
{
    use Queueable;
    private $task;

    /**
     * Create a new notification instance.
     */
    public function __construct(Task $task)
    {
        $this->task=$task;

    }


    public function via( $notifiable)
    {
        return ['database'];
    }

    public function toDataBase($notifiable)
    {



        return [
            "id"=> $this->task->created_by,
            "title"=> "a ajouter un rendez-vous",
            'user'=>Auth::user()->name,
            'image'=>Auth::user()->image,


        ];
    }

}
