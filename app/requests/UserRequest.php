<?php

namespace App\Requests;

use Core\Request;

class UserRequest extends Request
{
    public function getRules()
    {
        return [
            'firstName' => ['required', 'string', 'minLength:2', 'maxLength:40'],
            'lastName' => ['required', 'string', 'minLength:2', 'maxLength:40'],
            'email' => ['required', 'email', 'maxLength:255'],
            'password' => ['required', 'string', 'minLength:5'],
        ];
    }
}