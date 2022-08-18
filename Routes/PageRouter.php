<?php

/**
 * Router class
 * Stores the route table and calls corresponding to URLs functions
 */

namespace Expo\Routes;

class PageRouter extends Router
{
    protected static array $callbacks = [];
    protected static array $idSpecificPages = [];

    public static function callback(string $requestUri)
    {
        /**
         * requested URI structure:
         * /profile/18264772/edit?user=8376583#email
         * | main  |    list     |   query    | fragment
         *
         * main - $requestMain - determines which controller is called next
         * list - $requestList - parsed here into an array, passed further as argument
         * query - $requestQuery - parsed here into an array from string $queryString, passed further as argument
         * fragment - handled by browser, not known to server
         */

        list($requestMain, $requestList, $requestQuery) = self::parse($requestUri);

        if (isset(static::$callbacks[$requestMain])) {
            if (in_array($requestMain, static::$idSpecificPages)) {
                if (!isset($requestList[0]) || !ctype_digit($requestList[0])) {
                    return call_user_func_array(static::$callbacks['404'], [$requestList]);
                }
            }
            return call_user_func_array(static::$callbacks[$requestMain], [$requestList]);
        }

        return call_user_func_array(static::$callbacks['404'], [$requestList]);
    }

    private static function parse(string $uri): array
    {
        if ('/' === $uri[0]) {
            $uri = substr($uri, 1);
        }

        list($uri, $queryString) = array_pad(explode('?', $uri), 2, '');
        parse_str($queryString, $query);

        $list = explode('/', $uri);
        $main = array_shift($list);

        return [$main, $list, $query];
    }

    // ban on construction and cloning
    private function __construct()
    {
    }

    private function __clone()
    {
    }
}
