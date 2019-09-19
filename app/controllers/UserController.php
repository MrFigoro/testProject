<?php

namespace App\Controllers;

class UserController
{
    public function show(int $id)
    {
        return $id;
    }

    public function showParams(string $param, string $param2)
    {
        return "all right";
    }
}