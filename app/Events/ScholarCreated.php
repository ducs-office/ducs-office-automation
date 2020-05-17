<?php

namespace App\Events;

use App\Models\Scholar;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ScholarCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $scholar;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Scholar $scholar)
    {
        $this->scholar = $scholar;
    }
}
