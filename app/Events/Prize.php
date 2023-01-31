<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Prize implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $msg;
    public function __construct($msg)
    {
        $this->msg = $msg;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('three_good');
    }

    /**
     * 重新定义事件的广播名称 就是 Prize 的名称
     *
     * @return string
     */
//    public function broadcastAs()
//    {
//        return 'server.created';
//    }

    /**
     *获得广播数据，对广播的数据进行更仔细的处理， 默认这个事件 public 属性作为这个事件的广播出去的数据
     *
     * @return array
     */
//    public function broadcastWith()
//    {
//        return ['id' => $this->user->id];
//    }

    /**
     * 判断是否应该广播事件
     *
     * @return bool
     */
//    public function broadcastWhen()
//    {
//        // 条件为真时，进行广播
//        return $this->order->value > 100;
//    }
}
