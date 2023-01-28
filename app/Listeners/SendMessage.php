<?php
// 监听类
namespace App\Listeners;

use App\Events\RegisterOk;
use Illuminate\Support\Facades\Log;

class SendMessage
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\RegisterOk  $event
     * @return void
     */
    public function handle(RegisterOk $event)
    {
        Log::info('日志'.$event->user->username);
        dump('test');
        // 取消冒泡 当有多个监听器的时候，取消后面的方法
        return false;
    }
}
