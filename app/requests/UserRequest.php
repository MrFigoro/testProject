<?php

namespace App\Requests;

use Core\Request;

class UserRequest extends Request
{
    public function getRules()
    {
        return [
            'firstName' => ['required', 'string', 'maxLength:40'],
            'lastName' => ['required', 'string', 'maxLength:40'],
            'email' => ['required', 'string', 'email', 'maxLength:255', 'unique:users'],
            'password' => ['required', 'string', 'minLength:5'],
        ];
    }
}