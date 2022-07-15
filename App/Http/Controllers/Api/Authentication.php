<?php

namespace Expo\App\Http\Controllers\Api;

use Exception;
use Expo\App\Http\Controllers\HashHandler;
use Expo\App\Models\QueryBuilder as QB;

class Authentication
{
    public static function authenticate(string $type)
    {
        $hash = HashHandler::getHash('password', $_POST['password'], $_POST['email']);
        $post = $_POST;
        unset($post['password']);
        $post['passwordHash'] = $hash;
        list($inputStatus, $error) = UserInputHandler::processPost($post);
        if ($inputStatus) {
            $authStatus = false;
            $data = null;
            if ('sign-in' == $type) {
                list($authStatus, $data) = self::signIn($post);
            } elseif ('sign-up' == $type) {
                list($authStatus, $data) = self::signUp($post);
            }
            if ($authStatus) {
                header("Location: /profile/$data");
            } else {
                $uriQuery = http_build_query(['message' => $data, 'color' => 'red']);
                header("Location: /$type?$uriQuery");
            }
        } else {
            $uriQuery = http_build_query(['message' => $error, 'color' => 'red']);
            header("Location: /$type?$uriQuery");
        }
    }

    /**
     * @throws Exception
     */
    private static function signUp(array $credentials): array
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
                return [false, 'Email уже зарегистрирован. Пожалуйста, воспользуйтесь формой входа.'];
            }
        } else {
            throw new Exception('Insufficient information for sign-up.');
        }
    }

    /**
     * @throws Exception
     */
    private static function signIn(array $credentials): array
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
                return [false, 'Email не зарегистрирован. Пожалуйста, воспользуйтесь формой регистрации.'];
            }
        } else {
            throw new Exception('Insufficient information for sign-in.');
        }
    }

    public static function signOut()
    {
        if (isset($_COOKIE['authenticatedUserIDHash'])) {
            unset($_COOKIE['authenticatedUserIDHash']);
            setcookie('authenticatedUserIDHash', '', 1, '/');
        }
        if (isset($_COOKIE['userID'])) {
            unset($_COOKIE['userID']);
            setcookie('userID', '', 1, '/');
        }
        header("Location: /");
    }

    private static function saveHashToCookie(int $userID)
    {
        setcookie('authenticatedUserIDHash', HashHandler::getHash('id', $userID), time() + 10 * 24 * 3600, '/');
        setcookie('userID', $userID, time() + 10 * 24 * 3600, '/');
    }

    public static function getUserIdFromCookie(): int
    {
        if (isset($_COOKIE['userID']) && isset($_COOKIE['authenticatedUserIDHash']) && $_COOKIE['userID'] > 0) {
            if (HashHandler::getHash('id', $_COOKIE['userID']) == $_COOKIE['authenticatedUserIDHash']) {
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
