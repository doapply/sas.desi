<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeviceLogOut implements ShouldBroadcast
{

    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $deviceId;

    public function __construct($deviceId)
    {
        $this->deviceId = $deviceId;
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('device-logout');
    }
    public function broadcastAs()
    {
        return "device-logout";
    }
}
