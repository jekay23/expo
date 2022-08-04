<?php

namespace Expo\App\Http\Controllers;

class HashHandler
{
    public static function getHash(string $type, string $string, string $extraSalt = ''): string
    {
        $types = ['password', 'id', 'filename', 'token'];
        if (in_array($type, $types)) {
            $salt = HashCredentials::getSalt($type);
            $extraSalt = trim($extraSalt);
            return hash('sha256', $string . $salt . $string . $extraSalt);
        } else {
            throw new \Exception('Unknown hash type');
        }
    }
}
