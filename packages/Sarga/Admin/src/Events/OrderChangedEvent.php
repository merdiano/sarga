<?php

namespace Sarga\Admin\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderChangedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(protected $data)
    {
    }

    /**
     * Broadcast with data.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return $this->data;
    }

    public function broadcastOn()
    {
        return new Channel('notification');
    }

    /**
     * Seperate queue.
     *
     * Command: `php artisan queue:work --queue=broadcastable`
     *
     * @return string
     */
    public function broadcastQueue()
    {
        return 'broadcastable';
    }

    /**
     * Get the channels the event should broadcast as.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'update-notification';
    }
}