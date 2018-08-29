<?php

function isPost()
{
    return $_SERVER['REQUEST_METHOD'] == 'POST';
}

function getParam($name)
{
    return !empty($_REQUEST[$name]) ? $_REQUEST[$name] : '';
}

function staticCall($class, $function, $args = array())
{
    if (class_exists($class) && method_exists($class, $function))
        return call_user_func_array(array($class, $function), $args);
    return null;
}

function autoloader($className)
{
    $dirs = [
        'app/controllers/',
        'app/core/',
        'app/models/'
    ];
    foreach ($dirs as $dir) {
        $file = $dir . $className. '.php';
        if (file_exists($file) and !class_exists($className)) {
            require_once $file;
        }
    }
}