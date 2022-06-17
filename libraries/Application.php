<?php

class Application
{
    public static function process()
    {
        $controllerName = "Article";
        $task = "index";

        if (!empty($_GET['controller'])) {
            $controllerName = strip_tags(ucfirst($_GET['controller']));
        }

        if (!empty($_GET['task'])) {
            $task = strip_tags(ucfirst($_GET['task']));
        }

        $controllerName = "\Controllers\\" . $controllerName;

        $controller = new $controllerName();
        $controller->$task();
    }
}
