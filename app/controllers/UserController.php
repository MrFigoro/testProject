<?php

namespace App\Controllers;

use Core\Controllers\Controller;

class UserController extends Controller
{
    public function all()
    {
        return $this->render('user', ['message' => 'All right!']);
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