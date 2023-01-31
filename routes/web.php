<?php

use App\Events\Free;
use App\Events\Prize;
use App\Events\RegisterOk;
use App\Http\Controllers\Index\IndexController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/index',[IndexController::class,'index']);
// 事件
Route::get('/', function () {
//    $user = new UserRepository();
//    $user->login(123,123);
//    event(new RegisterOk($user));
    view('welcome');
});

// 公共广播的使用
Route::get('/event',function (){
    broadcast(new Free('广播开始了'));
});
