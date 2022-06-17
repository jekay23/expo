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
    public static function route($uriMain, $callback)
    {
        self::$routes[$uriMain] = $callback;
    }


    // check if the requested URI matches a known pattern, if so - call the callback function
    public static function execute($requestUri)
    {
        /**
         * URI structure:
         * /profile/18264772/edit?user=8376583#email
         * | main  |    list     |   query    | fragment
         *
         * main - $requestMain - determines which controller is called next
         * list - $requestList - parsed here into an array, passed further as argument
         * query - $query - parsed here into an array from string $queryString, passed further as argument
         * fragment - handled by browser, not known to server
         */

        // remove the preceding '/'
        if ('/' == $requestUri[0]) {
            $requestUri = substr($requestUri, 1);
        }

        // detach and parse the query string
        list($requestUri, $queryString) = array_pad(explode('?', $requestUri), 2, '');
        parse_str($queryString, $query);

        // parse the remaining part into main (string) and list (array of strings)
        $requestList = explode('/', $requestUri);
        $requestMain = array_shift($requestList);

        if (isset(self::$routes[$requestMain])) {
            return call_user_func_array(self::$routes[$requestMain], array($requestList, $query));
        }

        // page not found, call 404
        return call_user_func_array(self::$routes['404'], array($requestList, $query));
    }
}
