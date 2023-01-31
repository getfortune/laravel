# laravel

laravel 复习项目



# 配置输出日志

```php
<?php
// 日志配置文件
use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Deprecations Log Channel
    |--------------------------------------------------------------------------
    |
    | This option controls the log channel that should be used to log warnings
    | regarding deprecated PHP and library features. This allows you to get
    | your application ready for upcoming major versions of dependencies.
    |
    */

    'deprecations' => env('LOG_DEPRECATIONS_CHANNEL', 'null'),

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
    |
    */

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['daily'], // 此时会将日志按照日来进行划分
            'ignore_exceptions' => false,
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 14,
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Laravel Log',
            'emoji' => ':boom:',
            'level' => env('LOG_LEVEL', 'critical'),
        ],

        'papertrail' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => SyslogUdpHandler::class,
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
            ],
        ],

        'stderr' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => StreamHandler::class,
            'formatter' => env('LOG_STDERR_FORMATTER'),
            'with' => [
                'stream' => 'php://stderr',
            ],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        'null' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],

        'emergency' => [
            'path' => storage_path('logs/laravel.log'),
        ],
    ],

];

```





# 设置跨域

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

// 处理跨域第一步
class EnableCrossRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $origin = $request->server('HTTP_ORIGIN') ? $request->server('HTTP_ORIGIN') : '';
        //允许进行跨域请求的地址
        $allow_origin = [
            'http://localhost:3000', // 前端项目的地址
            'http://127.0.0.1:3000'  // localhost 和 127.0.0.1 有区别暂时还不知道为什么。
        ];
        if (in_array($origin, $allow_origin)) {
            $response->header('Access-Control-Allow-Origin',$origin);
            $response->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Cookie, X-CSRF-TOKEN, Accept, Authorization, X-XSRF-TOKEN');
            $response->header('Access-Control-Expose-Headers', 'Authorization, authenticated');
            $response->header('Access-Control-Allow-Methods', 'GET, POST, PATCH, PUT, OPTIONS');
            $response->header('Access-Control-Allow-Credentials', 'true');
        }
        return $response;
    }
}


// 在 kernel.php 中 添加中间件
protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        // 处理跨域第二步
        EnableCrossRequestMiddleware::class,
    ];
```



# 事件的使用

## 方便使用配置

在事件服务提供者中设置数据查询统一返回数组

```php
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

```

使用redis 进行频道的广播最好将 laravel 本身自带的 laravel_database_ 前缀注释掉



## 公共频道

php artisan  make:event  eventName

```php
<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Free implements ShouldBroadcast  // 1.事件是要广播出去的
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
        //2. 要广播出去的数据
        $this->msg = $msg;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn() // 3.要广播的频道
    {
        // 广播到多个频道上面
        return [new Channel('Countryside'),new Channel('a')];
    }

}

```



# 配置 passport 

1. 设置用户未授权时的提示语句。

   ```php
   <?php
   
   namespace App\Http\Middleware;
   
   use Illuminate\Auth\Middleware\Authenticate as Middleware;
   
   class Authenticate extends Middleware
   {
       /**
        * Handle an incoming request.
        *
        * @param \Illuminate\Http\Request $request
        * @param \Closure                 $next
        * @param string[]                 ...$guards
        *
        * @return mixed
        *
        * @throws \Illuminate\Auth\AuthenticationException
        */
       public function handle($request, Closure $next, ...$guards)
       {
           $res = $this->authenticate($request, $guards);
   
           if (false === $res) {
               return ['status' => false, 'message' => '-999', 'data' => ''];
           }
   
           return $next($request);
       }
   
       /**
        * Determine if the user is logged in to any of the given guards.
        *
        * @param \Illuminate\Http\Request $request
        * @param array                    $guards
        *
        * @return mixed
        */
       protected function authenticate($request, array $guards)
       {
           if (empty($guards)) {
               $guards = [null];
           }
   
   
           foreach ($guards as $guard) {
               if ($this->auth->guard($guard)->check()) {
                   return $this->auth->shouldUse($guard);
               }
           }
   
           return false;
       }
   
       /**
        * Get the path the user should be redirected to when they are not authenticated.
        *
        * @param  \Illuminate\Http\Request  $request
        * @return string|null
        */
       protected function redirectTo($request)
       {
           if (! $request->expectsJson()) {
               return route('login');
           }
       }
   }
   
   ```

2. 设置Model 方便来查找用户

   ```php
   <?php
   
   namespace App\Models;
   
   use Illuminate\Contracts\Auth\MustVerifyEmail;
   use Illuminate\Database\Eloquent\Factories\HasFactory;
   use Illuminate\Foundation\Auth\User as Authenticatable;
   use Illuminate\Notifications\Notifiable;
   use Laravel\Sanctum\HasApiTokens;
   
   class User extends Authenticatable
   {
       use HasApiTokens, HasFactory, Notifiable;
   
       // 关联的表名
       protected $table = 'student';
   
       // 表的主键
       protected $primaryKey = 'id';
   
       // 主键的类型
       protected $keyType = "int";
   
       /**
        * 指示模型是否主动维护时间戳。 updated_at ,created_at
        * 可以自定义维护字段的名字
        * const CREATED_AT = 'creation_date';
        * const UPDATED_AT = 'updated_date';
        * @var bool
        */
       public $timestamps = false;
   
       /**
        * The attributes that should be hidden for serialization.
        *
        * @var array<int, string>
        */
       // 隐藏属性那些是不可看的
   //    protected $hidden = [
   //        'password',
   //        'remember_token',
   //    ];
   
       /**
        * The attributes that should be cast.
        *
        * @var array<string, string>
        */
       // 任意属性的类型转换
   //    protected $casts = [
   //        'email_verified_at' => 'datetime',
   //    ];
   
   
   }
   
   ```

3. 其他的步骤跟着官网走，需要执行数据库迁移的命令，数据库迁移需要修改数据库默认字符串大小

   ```php
   <?php
   
   namespace App\Providers;
   
   use Illuminate\Support\Facades\Schema;
   use Illuminate\Support\ServiceProvider;
   
   class AppServiceProvider extends ServiceProvider
   {
       /**
        * Register any application services.
        *
        * @return void
        */
       public function register()
       {
           //
       }
   
       /**
        * Bootstrap any application services.
        *
        * @return void
        */
       public function boot()
       {
           //
         Schema::defaultStringLength(191);
       }
   }
   
   ```

   

