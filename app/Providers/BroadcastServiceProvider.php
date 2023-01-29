<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 注册 /broadcasting/auth 到路由去处理授权请求
        Broadcast::routes();

        require base_path('routes/channels.php');
    }
}
