<?php

namespace App\Requests;

use Core\Request;

class UserRequest extends Request
{
    public function getRules()
    {
        return [
            'firstName' => ['required', 'string', 'max:40'],
            'lastName' => ['string', 'max:40'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:5'],
        ];
    }
}