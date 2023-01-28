<?php
// 事件类
namespace App\Events;

use App\Repository\Contracts\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
// 事件
class RegisterOk
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    /**
     * Create a new event instance.
     *
     * @params $user 必须是 UserRepository 的实例
     * @return void
     */
    public function __construct(User $user)
    {
        // 这里是要传递给监听器的数据
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    // 广播
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
