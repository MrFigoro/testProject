<?php

namespace Core\Routering;

use ReflectionMethod;

class Route
{

    static function find()
    {
        $route = null;
        $className = null;
        $routes = require_once('../routes/web.php');
        $currentURL = $_SERVER['REQUEST_URI'];

        if (array_key_exists($currentURL, $routes)) {
            $route = $currentURL;
        } else {
            $countParamURL = count(explode('/', $currentURL));
            foreach (array_keys($routes) as $key) {
                $countParamsRoute = count(explode('/', $key));
                if ($countParamsRoute == $countParamURL) {
                    $quotedKey = str_replace('/', '\/', $key);
                    $regex = '/'.preg_replace('/{[^}]{1,}}/', '[^\/]{1,}', $quotedKey).'\z/';
                    if (preg_match($regex, $currentURL,$matches)) {
                        $route = $key;
                        break;
                    }
                }
            }
        }
        if (!is_null($route)) {
            $classAction = explode('@' ,$routes[$route]);
            if (count($classAction) == 2 ) {
                return self::dispatch($classAction[0], $classAction[1], self::prepareParams($route, $currentURL));
            }
        }
        self::errorPage404("This route is absent");
    }

    static function prepareParams(string $route, string $url)
    {
        $routeArray = explode('/', $route);
        $urlArray = explode('/', $url);
        // сохраняю ключи, которые отличаются ;)
        $keyV = array_diff($routeArray, $urlArray);
        foreach ($keyV as &$el) {
            $el = trim($el, '{}'); //обрезал фигурные скобки
        }
        unset($el); //обязательно надо убирать более неиспользуемую переменную
        $values = array_diff($urlArray, $routeArray); //получил массив отличающихся значений
        $params = array_combine($keyV, $values); //получил массив - назв. параметра и полученное значение
        return $params;
    }

    static function dispatch(string $controller, string $action, array $params = [])
    {
        try {
            $controller = '\App\Controllers\\'.$controller;
            if (!class_exists($controller)) {
                throw new \Exception('Controller not found');
            }
            $class = new $controller();
            if (!method_exists($class, $action)) {
                throw new \Exception('Action not found');
            }

            $method = new ReflectionMethod($class, $action);
            $mParams = $method->getParameters();
            if (count($mParams) == count($params)) {
                foreach ($mParams as $param) {
                    if (!array_key_exists($param->getName(), $params)) {
                        throw new \Exception('Incorrect params');
                    }
                }
                return $class->$action(...array_values($params));
            }
            throw new \Exception('Incorrect params');
        } catch (\Exception $exception) {
            self::errorPage404($exception->getMessage());
        };

    }

    static function errorPage404(string $message)
    {
        echo $message;
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header('HTTP/1.1 404 Not Found');
        header("Status: 404 Not Found");
        header('Location:'.$host.'404');
        header($message);
        die();
    }
}