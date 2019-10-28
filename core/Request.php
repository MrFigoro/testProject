<?php

namespace Core;

class Request
{
    /**
     * параметры, которые приходят из тела запроса
     * @var array $post
     */
    protected $post = [];

    /**
     * хранит ошибки
     * @var array $errors
     */
    protected $errors = [];

    public function __construct()
    {
        $this->post = json_decode(file_get_contents('php://input'),JSON_OBJECT_AS_ARRAY);
        if (!is_array($this->post)) {
            throw new \Exception('Invalid data!');
        }
    }

    public function getParam(String $name)
    {
        if (array_key_exists($name, $this->post)) {
            return $this->post[$name];
        }
    }

    public function validate()
    {
        //сама валидация
        return empty($this->errors);
    }
}