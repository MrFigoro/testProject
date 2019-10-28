<?php

namespace App\Controllers;

use Core\Controllers\Controller;
use App\Requests\UserRequest;

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
        return $param." или ".$param2;
    }

    public function create(UserRequest $request)
    {
        return ('Created!!!' . $request['firstName']);
    }
}