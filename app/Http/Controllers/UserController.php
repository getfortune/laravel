<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function login() {
        $user = User::where('id','=',3)->first();
        $token = $user->createToken('user')->accessToken;

        return ['token' => $token];
    }
}
