<?php

namespace Expo\App\Http\Controllers\Api;

use Exception;
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

    private static function checkEmailIsNew(string $email): bool
    {
        list($emailInDB,) = QB::checkEmailInDB($email);
        return !$emailInDB;
    }
}
