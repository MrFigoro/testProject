<?php
return [
    '' => 'HomeController@index',
    '/users' => 'UsersConroller@index',
    '/users/edit/{id}' => 'UsersConroller@edit',
    '/users/show/{id}' => 'UsersController@show',
    '/users/show/{param}/{param2}' => 'UsersController@showParams'
];