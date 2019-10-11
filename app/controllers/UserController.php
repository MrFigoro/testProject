<?php

namespace App\Controllers;

class UserController
{
    public function all()
    {
        return 'UserController works hard';
    }

    public function show(int $id)
    {
        return $id;
    }

    public function showParam(string $param, string $param2)
    {
        return $param.", ".$param2;
    }
}