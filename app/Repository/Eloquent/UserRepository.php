<?php

namespace App\Repository\Eloquent;

use App\Repository\Contracts\User;

class UserRepository implements User
{
    public $username;
    public $password;
    public function login($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }
}
