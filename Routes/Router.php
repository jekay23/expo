<?php

namespace Expo\Routes;

class Router
{
    // storage of {path => controller name} pairs
    private static $routes = array();

    // ban on construction and cloning
    // ? Can I put these in two lines instead of 6 (put "{}" in the end in spite of PSR-12)?
    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public static function route($pathMain, $controllerName)
    {
        $controllerName = 'Expo\\App\\Http\\' . $controllerName;
        self::$routes[$pathMain] = $controllerName;
    }

    public static function execute($urlMain, $urlSecondary)
    {
        foreach (self::$routes as $pathMain => $controllerName) {
            if ($pathMain == $urlMain) {
                $controllerName::openPage($urlSecondary);
            }
        }
    }
}
