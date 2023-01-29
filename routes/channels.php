<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


// 设置频道权限
Broadcast::channel('three_good', function ($user, $id) {
//    $users = DB::table('student')->where(['id', '<',5])->get(['id'])->toArray();
    $users = [1, 2, 3, 4, 5];
    return in_array($user->id,$users);
//    return true;
});
