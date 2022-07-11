<?php

namespace Expo\App\Http\Controllers;

class HashHandler
{
    public static function getPasswordHash(string $string, string $extraSalt = ''): string
    {
        $salt = HashCredentials::getSalt('password');
        $extraSalt = trim($extraSalt);
        return hash('sha256', $string . $salt . $string . $extraSalt);
    }

    public static function getIDHash(string $string, string $extraSalt = ''): string
    {
        $salt = HashCredentials::getSalt('id');
        $extraSalt = trim($extraSalt);
        return hash('sha256', $string . $salt . $string . $extraSalt);
    }
}
