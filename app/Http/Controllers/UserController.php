<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function login() {
        $a = auth()->loginUsingId(3);

        return csrf_token();
    }
}
