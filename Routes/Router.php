<?php

namespace Expo\Routes;

class Router
{
    protected static array $callbacks = [];

    protected static array $idSpecificPages = [];

    public static function saveCallback(string $uriMain, callable $callback, bool $isIdSpecific = false)
    {
        if ($isIdSpecific) {
            static::$idSpecificPages[] = $uriMain;
        }
        static::$callbacks[$uriMain] = $callback;
    }
}
