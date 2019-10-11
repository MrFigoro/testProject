<?php
define("ROOT_DIR", dirname(__FILE__.'/', 2));

spl_autoload_register(function ($classname){
    var_dump($classname);
    $arr = explode('\\', $classname);
    for ($i = 0; $i < count($arr) - 1; $i++){
        $arr[$i] = strtolower($arr[$i]);
    }
    var_dump($arr);
    $class = implode($arr, DIRECTORY_SEPARATOR);
    var_dump($class);
    $path = ROOT_DIR.'/'.$class.'.php';
    var_dump($path);
    require_once($path);
}, true);

use Core\Routering\Route;

echo Route::find();