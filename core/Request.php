<?php

namespace Core;

class Request
{
    /**
     * параметры, которые приходят из тела запроса
     * @var array $data
     */
    protected $data = [];

    /**
     * хранит ошибки
     * @var array $errors
     */
    protected $errors = [];

    public function __construct()
    {
        $this->data = json_decode(file_get_contents('php://input'),JSON_OBJECT_AS_ARRAY);
        if (!is_array($this->data)) {
            throw new \Exception('Invalid data!');
        }
    }

    public function getParam(String $name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
    }

    public function validate()
    {
        foreach ($this->getRules() as $field => $rules) {
            if (in_array('required', $rules)) {
                unset($rules[array_search('required', $rules)]);
                if (!$this->required($field)) {
                    continue;
                }
            }
            foreach ($rules as $rule) {
                if (method_exists($this, $rule)) {
                    $this->$rule($field);
                } else {
                    $ruleValue = explode(':', $rule);
                    if (count($ruleValue) == 2) {
                        if (method_exists($this, $ruleValue[0])) {
                            $this->{$ruleValue[0]}($field, $ruleValue[1]);
                        }
                    }
                }
            }
        }
        var_dump($this->errors);
        return empty($this->errors);
    }

    protected function setError(string $field, string $error)
    {
        $this->errors[$field][] = $error;
    }

    public function required(string $field) : bool
    {
        if (empty($this->data[$field])) {
            $this->setError($field, 'This field is required');
            return false;
        }
        return true;
    }

    public function string(string $field) : void
    {
        if (!is_string($this->data[$field])) {
            $this->setError($field, 'This field must be string');
        }
    }

    public function email(string $field) : void
    {
        if (!filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->setError($field, 'This field must be like email');
        }
    }

    public function maxLength(string $field, int $maxLength) : void
    {
        if (strlen($this->data[$field]) > $maxLength) {
            $this->setError($field, 'The length of the field('.strlen($this->data[$field]).') have to be less than '. $maxLength);
        }
    }

    public function minLength(string $field, int $minLength) : void
    {
        if (strlen($this->data[$field]) < $minLength) {
            $this->setError($field, 'The length of the field('.strlen($this->data[$field]).') have to be more than '. $minLength);
        }
    }
}