<?php

namespace App\Repository\Contracts;

interface User
{
    public function login($username,$password);
}
