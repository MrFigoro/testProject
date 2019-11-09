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
        $rules = $this->getRules();
        $keyRules = array_keys($rules);
        foreach ($keyRules as $key) {
            var_dump('The pole: '. $key);
            foreach ($rules[$key] as $rule) {
                var_dump('The rule: '.$rule);
                if (method_exists($this, $rule)) {
                    if ($this->$rule($key)) {
                        break;
                    } else {
                        continue;
                    }
                }
                $ruleValue = explode(':', $rule);
                if (count($ruleValue) == 2) {
                    if (method_exists($this, $ruleValue[0])) {
                        $func = $ruleValue[0];
                        $this->$func($key, $ruleValue[1]);
                        continue;
                    }
                    var_dump('The method "'.$ruleValue[0].'" don\'t exist');
                    continue;
                }
                var_dump('The method "'.$rule.'" don\'t exist');
                continue;
            }
        }
        var_dump($this->errors);
        return empty($this->errors);
    }

    protected function setError(string $field, string $error)
    {
        $this->errors[$field][] = $error;
    }

    public function required(string $field)
    {
        if (empty($this->data[$field])) {
            $this->setError($field, 'This field is required');
            return true;
        }
        else return false;
    }

    public function string(string $field) : void
    {
        if (!is_string($this->data[$field])) {
            $this->setError($field, 'This field must be string');
        }
    }

    public function email(string $field) : void
    {
        if (!filter_var($field, FILTER_VALIDATE_EMAIL)) {
            $this->setError($field, 'This field must be like email');
        }
    }

    public function maxLength(string $field, int $maxLength) : void
    {
        if (strlen($field) > $maxLength) {
            $this->setError($field, 'The length of the field have to be less than '. $maxLength);
        }
    }

    public function minLength(string $field, int $minLength) : void
    {
        if (strlen($field) < $minLength) {
            $this->setError($field, 'The length of the field have to be more than '. $minLength);
        }
    }
}