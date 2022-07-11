<?php

namespace Expo\App\Http\Controllers\Api;

use Exception;
use Expo\App\Http\Controllers\HashHandler;
use Expo\App\Models\QueryBuilder as QB;

class Authentication
{
    /**
     * @throws Exception
     */
    public static function signUp(array $credentials): array
    {
        if (isset($credentials['email'], $credentials['name'], $credentials['passwordHash'])) {
            $emailIsNew = self::checkEmailIsNew($credentials['email']);
            if ($emailIsNew) {
                list($userCreated, $userID) = QB::createUser($credentials);
                if ($userCreated) {
                    self::saveHashToCookie($userID);
                    return [true, $userID];
                } else {
                    throw new Exception('Unknown error: ID accessible, but user not set as created.');
                }
            } else {
                return [false, 'Email уже зарегестрирован. Пожалуйста, воспользуйтесь формой входа.'];
            }
        } else {
            throw new Exception('Insufficient information for sign-up.');
        }
    }

    /**
     * @throws Exception
     */
    public static function singIn(array $credentials): array
    {
        if (isset($credentials['email'], $credentials['passwordHash'])) {
            list($emailInDB, $userID) = QB::checkEmailInDB($credentials['email']);
            if ($emailInDB) {
                list($authenticated, $error) = QB::authenticate($userID, $credentials['passwordHash']);
                if ($authenticated) {
                    self::saveHashToCookie($userID);
                    return [true, $userID];
                } else {
                    return [false, $error];
                }
            } else {
                return [false, 'Email не зарегестрирован. Пожалуйста, воспользуйтесь формой регистрации.'];
            }
        } else {
            throw new Exception('Insufficient information for sign-in.');
        }
    }

    private static function saveHashToCookie(int $userID)
    {
        setcookie('authenticatedUserIDHash', HashHandler::getIDHash($userID), time() + 10 * 24 * 3600, '/');
        setcookie('userID', $userID, time() + 10 * 24 * 3600, '/');
    }

    public static function getUserIdFromCookie(): int
    {
        if (isset($_COOKIE['userID']) && isset($_COOKIE['authenticatedUserIDHash']) && $_COOKIE['userID'] > 0) {
            if (HashHandler::getIDHash($_COOKIE['userID']) == $_COOKIE['authenticatedUserIDHash']) {
                return $_COOKIE['userID'];
            }
        }
        return 0;
    }

    private static function checkEmailIsNew(string $email): bool
    {
        list($emailInDB,) = QB::checkEmailInDB($email);
        return !$emailInDB;
    }
}
