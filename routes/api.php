<?php

use App\Events\Prize;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// laravel 路由快速登录
Route::any('/3', [UserController::class,'login']);

Route::group(['middleware' => ['auth:api']],function() {
    // 私有广播的使用
    Route::post('/private',function (){
        event(new Prize('3002'));
    });
});
Route::get('/8',function (){
    auth()->loginUsingId(8);
});
