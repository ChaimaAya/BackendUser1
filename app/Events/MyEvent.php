<?php

namespace App\Events;

use App\Models\Publication;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MyEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $publication;

    public function __construct(Publication $publication)
    {
        $this->publication = $publication;
    }

    public function broadcastOn()
    {
        return ['publication'];
    }

    public function broadcastAs()
    {
        return 'new-publication';
    }
}
