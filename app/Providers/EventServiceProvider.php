<?php

namespace App\Providers;

use App\Events\RegisterOk;
use App\Listeners\SendMessage;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Database\Events\StatementPrepared;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        //php artisan event:generate  这个命令会生成不存在的监听器和事件
        RegisterOk::class => [ // 事件,需要自己来绑定
            SendMessage::class,// 监听器 可以有多个
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //数据查询统一返回为数组
        Event::listen(StatementPrepared::class, function (StatementPrepared $event) {
            $event->statement->setFetchMode(\PDO::FETCH_ASSOC);
        });

//        //
//        # 在 boot 方法里，以闭包方式注册
//        // event('event.name', $user);
//        Event::listen('event.name', function ($user) {
//
//        });
//        # 通配符
//        Event::listen('event.*', function ($eventName, array $data) {
//            //
//        });
    }

    // 配置自动发现后可以不注册了，Laravel 会自动扫描目录。 一般不会使用效率比较低
    /*  避免每次请求扫描目录
        php artisan event:cache
        php artisan event:clear 当有事件重新更新的时候重新缓存一遍
    */
    //    # EventServiceProvider
    //    public function shouldDiscoverEvents()
    //    {
    //        return true;
    //    }
    //    // 配置自动扫描的目录
    //    protected function discoverEventsWithin()
    //    {
    //        return [
    //            $this->app->path('Listeners'),
    //        ];
    //    }
}
