<?php

namespace core\routering;

class Route
{
    static function start()
    {
        // модель, контроллер и действие по-умолчанию
        $modelName = 'Home';
        $controllerAction = 'HomeController@index';

        $routes = require_once('../routes/web.php');
        $route = $_SERVER['REQUEST_URI'];

        if (array_key_exists($route, $routes)) {
            $controllerAction = $routes[$route];
        } else {
            //извлекаю все ключи файла роутов (все роуты)
            $keysRoutes = array_keys($routes);

            //разбиваю полученный роут по /
            $sRoute = explode('/', $route);

            //разбиваю каждый роут в массиве ключей роутов и сравниваю длину полученного массива с длиной искомого
            foreach ($keysRoutes as $keysRoute) {
                $keyArray = explode('/', $keysRoute);
                if (count($keyArray) == count($sRoute)) {
                    //если длины равны, то сохраняю элементы, которые отличаются ;)
                    $keyV = array_diff($keyArray, $sRoute);
                    foreach ($keyV as &$el) {
                        $el = trim($el, '{}'); //обрезал фигурные скобки
                    }
                    unset($el); //обязательно надо убирать более неиспользуемую переменную
                    $values = array_diff($sRoute, $keyArray); //получил массив отличающихся значений
                    $params = array_combine($keyV, $values); //получил массив - назв. параметра и полученное значение
                    var_dump($params);
                    var_dump($routes[$keysRoute]);
                    exit;

                    // далее через reflections надо вызвать метод контроллера
                    $controllerAction = $routes[$route];
                }
            }
        }





        var_dump(array_key_exists($route, $routes));
        exit;

        $routes = explode('/', $_SERVER['REQUEST_URI']);



        // именование контроллера
        $controllerName = $modelName.'Controller';

        // действие контроллера
        // $actionName;

        // включение файла с классом модели (файла модели может и не быть)
        $modelFile = ucfirst(strtolower($modelName)).'.php';
        $modelPath = "app/models/".$modelFile;
        if(file_exists($modelPath))
        {
            include "app/models/".$modelFile;
        }

        // включение файла с классом контроллера
        $controllerFile = ucfirst(strtolower($controllerName)).'.php';
        $controllerPath = "app/controllers/".$controllerFile;
//        var_dump(file_exists($controllerPath));
//        die();
        if(file_exists($controllerPath))
        {
            include "app/controllers/".$controllerFile;
        }
        else
        {
            /*
            правильно было бы кинуть здесь исключение,
            но для упрощения сразу сделаем редирект на страницу 404
            */
            Route::ErrorPage404();
        }

//        var_dump($controllerName);
//        var_dump($actionName);
//        var_dump($_SERVER['HTTP_HOST']);
//        die();

        // создаем контроллер
        $controller = new $controllerName;
        $action = $actionName;

        if(method_exists($controller, $action))
        {
            // вызываем действие контроллера
            $controller->$action();
        }
        else
        {
            // здесь также разумнее было бы кинуть исключение
            Route::ErrorPage404();
        }

    }

    static function ErrorPage404()
    {
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header('HTTP/1.1 404 Not Found');
        header("Status: 404 Not Found");
        header('Location:'.$host.'404');
        die();
    }
}