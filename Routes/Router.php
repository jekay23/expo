<?php

/**
 * Router class
 * Stores the route table and calls corresponding to URLs functions
 */

namespace Expo\Routes;

class Router
{
    // storage of {pattern => function} pairs
    private static $routes = array();

    // ban on construction and cloning
    // ? Can I put these in two lines instead of 6 (put "{}" in the end in spite of PSR-12)?
    private function __construct()
    {
    }

    private function __clone()
    {
    }

    // build a regular-expression version of the pattern, and save the matching callback function
    public static function route($pattern, $callback)
    {
        $pattern = '/^' . str_replace('/', '\/', $pattern) . '$/';
        self::$routes[$pattern] = $callback;
    }


    // check if the requested URL matches a known pattern, if so - call the callback function
    public static function execute($url)
    {
        foreach (self::$routes as $pattern => $callback) {
            if (preg_match($pattern, $url, $params)) {
                array_shift($params);
                return call_user_func_array($callback, array_values($params));
            }
        }
        // here there'll be handling of incorrect URLs
    }
}
