<?php
return [
    '' => 'HomeController@index',
    '/users' => 'UserController@index',
    '/users/delete/{id}' => 'UserController@delete',
    '/users/edit/{id}' => 'UserController@edit',
    '/users/show/{id}' => 'UserController@show',
    '/users/show/{param}/{param2}' => 'UserController@showParams'
];