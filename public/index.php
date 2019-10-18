<?php
define("ROOT_DIR", dirname(__FILE__.'/', 2));

spl_autoload_register(function ($classname){
    $arr = explode('\\', $classname);
    for ($i = 0; $i < count($arr) - 1; $i++){
        $arr[$i] = strtolower($arr[$i]);
    }
    $class = implode($arr, DIRECTORY_SEPARATOR);
    $path = ROOT_DIR.'/'.$class.'.php';
    require_once($path);
}, true);

use Core\Routering\Route;

echo Route::find();