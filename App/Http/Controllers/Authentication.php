<?php

namespace Expo\App\Http\Controllers;

use Exception;
use Expo\App\Mail\EmailSender;
use Expo\App\Models\QueryBuilder as QB;
use Expo\App\Models\Tokens;
use Expo\App\Models\Users;

class Authentication
{
    /**
     * @throws Exception
     */
    public static function authenticate(string $type)
    {
        $hash = HashHandler::getHash('password', $_POST['password'], $_POST['email']);
        $post = $_POST;
        unset($post['password']);
        $post['passwordHash'] = $hash;
        list($inputStatus, $error) = QueryHandler::processPost($post);
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
                list($userCreated, $userID) = Users::createUser($credentials);
                if ($userCreated) {
                    self::saveHashToCookie($userID);
                    $token = self::getToken($userID, 'verify');
                    EmailSender::send('verify', $userID, ['verifyUrl' => Api::getUrlWithToken('verify', $token)]);
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
    private static function signIn(array $credentials, bool $saveCookie = true): array
    {
        if (isset($credentials['email'], $credentials['passwordHash'])) {
            list($emailInDB, $userID) = Users::checkEmailInDB($credentials['email']);
            if ($emailInDB) {
                list($authenticated, $error) = Users::authenticate($userID, $credentials['passwordHash']);
                if ($authenticated) {
                    if ($saveCookie) {
                        self::saveHashToCookie($userID);
                    }
                    return [true, $userID];
                } else {
                    return [false, $error];
                }
            } else {
                return [false, 'Неверная комбинация email и пароля'];
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

    /**
     * @throws Exception
     */
    public static function checkUserIsEditor(): bool
    {
        return self::checkUserHasSufficientLevel(2);
    }

    /**
     * @throws Exception
     */
    public static function checkUserIsAdmin(): bool
    {
        return self::checkUserHasSufficientLevel(3);
    }

    /**
     * @throws Exception
     */
    private static function checkUserHasSufficientLevel(int $minLevel): bool
    {
        $userID = self::getUserIdFromCookie();
        if ($userID) {
            $userLevel = Users::getUserLevel($userID);
            return self::compareUserLevel($userLevel, $minLevel);
        }
        return false;
    }

    /**
     * @param int $userLevel
     * @param int $condition
     * @param string{'geq', 'leq', 'greater', 'less', 'equal'} $comparisonType
     * @return bool
     * @throws Exception
     */
    private static function compareUserLevel(int $userLevel, int $condition, string $comparisonType = 'geq'): bool
    {
        switch ($comparisonType) {
            case 'geq':
                return ($userLevel >= $condition);
            case 'leq':
                return ($userLevel <= $condition);
            case 'greater':
                return ($userLevel > $condition);
            case 'less':
                return ($userLevel < $condition);
            case 'equal':
                return ($userLevel == $condition);
            default:
                throw new Exception('Unknown comparison type in Authentication::compareUserLevel()');
        }
    }

    private static function checkEmailIsNew(string $email): bool
    {
        list($emailInDB,) = Users::checkEmailInDB($email);
        return !$emailInDB;
    }

    public static function changePasswordEmail()
    {
        $post = $_POST;
        $userID = Authentication::getUserIdFromCookie();
        list($postStatus, $error) = QueryHandler::processPost($post);
        if (!$postStatus) {
            $uriQuery = http_build_query(['message' => $error, 'color' => 'red']);
            header("Location: /profile/$userID/change-password-email?$uriQuery");
            exit;
        }
        list($status, $user) = QB::getProfileData($userID, true);
        if (!$status) {
            $uriQuery = http_build_query(['message' => 'Пользователь не существует', 'color' => 'red']);
            header("Location: /?$uriQuery");
            exit;
        }
        $oldEmail = $user['email'];
        $oldHash = HashHandler::getHash('password', $post['oldPassword'], $oldEmail);
        list($status, $error) = self::signIn(['email' => $oldEmail, 'passwordHash' => $oldHash], false);
        if (!$status) {
            $uriQuery = http_build_query(['message' => $error, 'color' => 'red']);
            header("Location: /profile/$userID/change-password-email?$uriQuery");
            exit;
        }
        if ($oldEmail == $post['email']) {
            if (empty($post['newPassword']) && empty($post['newPasswordAgain'])) {
                $uriQuery = http_build_query(['message' => 'Вы ничего не изменили', 'color' => 'red']);
                header("Location: /profile/$userID/change-password-email?$uriQuery");
                exit;
            } elseif ($post['newPassword'] == $post['newPasswordAgain']) {
                $newHash = HashHandler::getHash('password', $post['newPassword'], $post['email']);
                $user = [
                    'userID' => $userID,
                    'passwordHash' => $newHash
                ];
                Users::updateProfileData($user);
                $uriQuery = http_build_query(['message' => 'Пароль изменён', 'color' => 'green']);
                header("Location: /profile/$userID?$uriQuery");
                exit;
            } else {
                $uriQuery = http_build_query(['message' => 'Пароли не совпадают', 'color' => 'red']);
                header("Location: /profile/$userID/change-password-email?$uriQuery");
                exit;
            }
        } elseif (Users::checkEmailInDB($post['email'])[0]) {
            $uriQuery = http_build_query(['message' => 'Профиль с данным email уже существует', 'color' => 'red']);
            header("Location: /profile/$userID/change-password-email?$uriQuery");
            exit;
        } elseif (empty($post['newPassword']) && empty($post['newPasswordAgain'])) {
            $newHash = HashHandler::getHash('password', $post['oldPassword'], $post['email']);
            $user = [
                'userID' => $userID,
                'email' => $post['email'],
                'passwordHash' => $newHash
            ];
            Users::updateProfileData($user);
            $uriQuery = http_build_query(['message' => 'Email изменён', 'color' => 'green']);
            header("Location: /profile/$userID?$uriQuery");
            exit;
        } elseif ($post['newPassword'] == $post['newPasswordAgain']) {
            $newHash = HashHandler::getHash('password', $post['newPassword'], $post['email']);
            $user = [
                'userID' => $userID,
                'email' => $post['email'],
                'passwordHash' => $newHash
            ];
            Users::updateProfileData($user);
            $uriQuery = http_build_query(['message' => 'Email и пароль изменены', 'color' => 'green']);
            header("Location: /profile/$userID?$uriQuery");
            exit;
        } else {
            $uriQuery = http_build_query(['message' => 'Пароли не совпадают', 'color' => 'red']);
            header("Location: /profile/$userID/change-password-email?$uriQuery");
            exit;
        }
    }

    /**
     * @throws Exception
     */
    public static function getToken(int $userID, string $type): string
    {
        $token = HashHandler::getHash('token', $userID, time());
        Tokens::add($userID, $token, $type);
        return $token;
    }

    /**
     * @throws Exception
     */
    public static function checkToken(int $userID, string $token, string $type): bool
    {
        return Tokens::check($userID, $token, $type);
    }

    /**
     * @throws Exception
     */
    public static function verifyEmail(string $token)
    {
        $hash = HashHandler::getHash('password', $_POST['password'], $_POST['email']);
        $post = $_POST;
        unset($post['password']);
        $post['passwordHash'] = $hash;
        list($inputStatus, $error) = QueryHandler::processPost($post);
        if ($inputStatus) {
            list($authStatus, $data) = self::signIn($post, false);
            if ($authStatus) {
                $tokenStatus = self::checkToken($data, $token, 'verify');
                if ($tokenStatus) {
                    Tokens::delete($token);
                    Users::verifyEmail($data);
                    $uriQuery = http_build_query(['message' => 'Email подтверждён', 'color' => 'green']);
                    header("Location: /profile/$data?$uriQuery");
                } else {
                    $uriQuery = http_build_query(['message' => 'Email не подтверждён', 'color' => 'red']);
                    header("Location: /?$uriQuery");
                }
            } else {
                $uriQuery = http_build_query(['message' => $data, 'color' => 'red']);
                header("Location: /verify?token=$token&$uriQuery");
            }
        } else {
            $uriQuery = http_build_query(['message' => $error, 'color' => 'red']);
            header("Location: /?$uriQuery");
        }
    }

    /**
     * @throws Exception
     */
    public static function requestRestore()
    {
        $post = $_POST;
        list($inputStatus, $error) = QueryHandler::processPost($post);
        if ($inputStatus) {
            $email = $post['email'];
            list($emailExists, $userID) = Users::checkEmailInDB($email);
            if ($emailExists) {
                $token = self::getToken($userID, 'restore');
                EmailSender::send('restore', $userID, ['restoreUrl' => Api::getUrlWithToken('restore', $token)]);
            }
            $uriQuery = http_build_query([
                'message' => "Если email $email зарегистрирован на сайте, на него придёт письмо c инструкциями",
                'color' => 'green'
            ]);
        } else {
            $uriQuery = http_build_query(['message' => $error, 'color' => 'red']);
        }
        header("Location: /request-restore?$uriQuery");
    }

    /**
     * @throws Exception
     */
    public static function restorePassword(string $token)
    {
        $hash = HashHandler::getHash('password', $_POST['password'], $_POST['email']);
        $hashAgain = HashHandler::getHash('password', $_POST['passwordAgain'], $_POST['email']);
        $post = $_POST;
        unset($post['password']);
        unset($post['passwordAgain']);
        $post['passwordHash'] = $hash;
        $post['passwordHashAgain'] = $hashAgain;
        list($inputStatus, $error) = QueryHandler::processPost($post);
        if ($inputStatus) {
            if ($post['passwordHash'] != $post['passwordHashAgain']) {
                $uriQuery = http_build_query(['message' => 'Пароли не совпадают', 'color' => 'red', 'token' => $token]);
                header("Location: /restore?$uriQuery");
                exit;
            }
            list($emailExists, $userID) = Users::checkEmailInDB($post['email']);
            if ($emailExists) {
                $tokenStatus = self::checkToken($userID, $token, 'restore');
                if ($tokenStatus) {
                    Tokens::delete($token);
                    Users::changePassword($userID, $post['passwordHash']);
                    $uriQuery = http_build_query(['message' => 'Пароль изменён', 'color' => 'green']);
                    header("Location: /sign-in?$uriQuery");
                    exit;
                }
            }
            $uriQuery = http_build_query(['message' => 'Пароль не изменён', 'color' => 'red']);
        } else {
            $uriQuery = http_build_query(['message' => $error, 'color' => 'red']);
        }
        header("Location: /request-restore?$uriQuery");
    }
}
