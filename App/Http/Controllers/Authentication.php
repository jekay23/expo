<?php

namespace Expo\App\Http\Controllers;

use Exception;
use Expo\App\Mail\EmailSender;
use Expo\App\Models\Entities\Tokens;
use Expo\App\Models\Entities\Users;
use Expo\Config\ExceptionWithUserMessage;
use Expo\Resources\Views\Html;

class Authentication
{
    /**
     * @throws Exception
     */
    public static function authenticate(string $type)
    {
        $post = $_POST;
        try {
            HTTPQueryHandler::validateAndProcessPostWithPassword($post);
        } catch (ExceptionWithUserMessage $e) {
            Api::openPageWithUserMessage("/$type", $e->getMessage());
            exit;
        }
        try {
            if ('sign-in' == $type) {
                $userID = self::signIn($post);
            } elseif ('sign-up') {
                $userID = self::signUp($post);
            } else {
                throw new Exception('Unknown authentication type');
            }
        } catch (ExceptionWithUserMessage $e) {
            Api::openPageWithUserMessage("/$type", $e->getMessage());
            exit;
        }
        header("Location: /profile/$userID");
    }

    /**
     * @throws ExceptionWithUserMessage
     * @throws Exception
     */
    private static function signUp(array $credentials): int
    {
        if (isset($credentials['email'], $credentials['name'], $credentials['passwordHash'])) {
            if (self::isEmailNew($credentials['email'])) {
                $userID = Users::create($credentials);
                self::saveHashToCookie($userID);
                $token = self::getToken($userID, 'verify');
                EmailSender::send('verify', $userID, ['verifyUrl' => self::getUrlWithToken('verify', $token)]);
                return $userID;
            } else {
                throw new ExceptionWithUserMessage(
                    'Email уже зарегистрирован. Пожалуйста, воспользуйтесь формой входа.'
                );
            }
        } else {
            throw new Exception('Insufficient information for sign-up.');
        }
    }

    /**
     * @throws ExceptionWithUserMessage
     * @throws Exception
     */
    private static function signIn(array $credentials, bool $saveCookie = true): int
    {
        if (isset($credentials['email'], $credentials['passwordHash'])) {
            $userID = Users::getIdByEmail($credentials['email']);
            if ($userID) {
                $authenticated = Users::authenticate($userID, $credentials['passwordHash']);
                if ($authenticated) {
                    if ($saveCookie) {
                        self::saveHashToCookie($userID);
                    }
                    return $userID;
                }
            }
            throw new ExceptionWithUserMessage('Неверная комбинация email и пароля');
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

    /**
     * @throws Exception
     */
    private static function saveHashToCookie(int $userID)
    {
        setcookie('authenticatedUserIDHash', HashHandler::getHash('id', $userID), time() + 10 * 24 * 3600, '/');
        setcookie('userID', $userID, time() + 10 * 24 * 3600, '/');
    }

    public static function getUserIdFromCookie(): int
    {
        if (isset($_COOKIE['userID']) && isset($_COOKIE['authenticatedUserIDHash']) && $_COOKIE['userID'] > 0) {
            try {
                if (HashHandler::getHash('id', $_COOKIE['userID']) == $_COOKIE['authenticatedUserIDHash']) {
                    return $_COOKIE['userID'];
                }
            } catch (Exception $e) {
                return 0;
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
            $userLevel = Users::getAccessLevel($userID);
            return self::compareUserLevel($userLevel, $minLevel);
        }
        return false;
    }

    /**
     * @param int $userLevel
     * @param int $condition
     * @param string $comparisonType 'geq' | 'leq' | 'greater' | 'less' | 'equal'
     * @return bool
     * @throws Exception
     */
    private static function compareUserLevel(int $userLevel, int $condition, string $comparisonType = 'geq'): bool
    {
        $comparisonResults = [
            'geq' => ($userLevel >= $condition),
            'leq' => ($userLevel <= $condition),
            'greater' => ($userLevel > $condition),
            'less' => ($userLevel < $condition),
            'equal' => ($userLevel == $condition)
        ];
        if (isset($comparisonResults[$comparisonType])) {
            return $comparisonResults[$comparisonType];
        } else {
            throw new Exception('Unknown comparison type');
        }
    }

    /**
     * @throws Exception
     */
    private static function isEmailNew(string $email): bool
    {
        $userID = Users::getIdByEmail($email);
        return !$userID;
    }

    /**
     * @throws Exception
     */
    private static function changeOnlyPassword(array $post, int $userID)
    {
        if ($post['newPassword'] == $post['newPasswordAgain']) {
            $newHash = HashHandler::getHash('password', $post['newPassword'], $post['email']);
            $user = [
                'userID' => $userID,
                'passwordHash' => $newHash
            ];
            Users::updateProfileData($user);
            Api::openPageWithUserMessage("/profile/$userID", 'Пароль изменён', 'green');
        } else {
            Api::openPageWithUserMessage("/profile/$userID/change-password-email", 'Пароли не совпадают');
        }
    }

    /**
     * @throws Exception
     */
    private static function changeOnlyEmail(array $post, int $userID)
    {
        // password hash is formed with email as salt
        $newHash = HashHandler::getHash('password', $post['oldPassword'], $post['email']);
        $user = [
            'userID' => $userID,
            'email' => $post['email'],
            'passwordHash' => $newHash
        ];
        Users::updateProfileData($user);
        Api::openPageWithUserMessage("/profile/$userID", 'Email изменён', 'green');
    }

    /**
     * @throws Exception
     */
    private static function changeBothPasswordAndEmail(array $post, int $userID)
    {
        if ($post['newPassword'] == $post['newPasswordAgain']) {
            $newHash = HashHandler::getHash('password', $post['newPassword'], $post['email']);
            $user = [
                'userID' => $userID,
                'email' => $post['email'],
                'passwordHash' => $newHash
            ];
            Users::updateProfileData($user);
            Api::openPageWithUserMessage("/profile/$userID", 'Email и пароль изменены', 'green');
        } else {
            Api::openPageWithUserMessage("/profile/$userID/change-password-email", 'Пароли не совпадают');
        }
    }

    /**
     * @throws ExceptionWithUserMessage
     * @throws Exception
     */
    public static function changePasswordOrEmail()
    {
        $post = $_POST;
        $userID = Authentication::getUserIdFromCookie();
        try {
            HTTPQueryHandler::validateAndProcessPost($post);
        } catch (ExceptionWithUserMessage $e) {
            Api::openPageWithUserMessage("/profile/$userID/change-password-email", $e->getMessage());
            exit;
        }
        $user = Users::getUserData($userID, true);
        $oldEmail = $user['email'];
        $oldHash = HashHandler::getHash('password', $post['oldPassword'], $oldEmail);
        try {
            self::signIn(['email' => $oldEmail, 'passwordHash' => $oldHash], false);
        } catch (ExceptionWithUserMessage $e) {
            Api::openPageWithUserMessage("/profile/$userID/change-password-email", $e->getMessage());
            exit;
        }
        if ($oldEmail == $post['email']) {
            if (empty($post['newPassword']) && empty($post['newPasswordAgain'])) {
                Api::openPageWithUserMessage("/profile/$userID/change-password-email", 'Вы ничего не изменили');
            } else {
                self::changeOnlyPassword($post, $userID);
            }
        } elseif (Users::getIdByEmail($post['email'])) {
            Api::openPageWithUserMessage("/profile/$userID/change-password-email", 'Недопустимый email');
        } elseif (empty($post['newPassword']) && empty($post['newPasswordAgain'])) {
            self::changeOnlyEmail($post, $userID);
        } else {
            self::changeBothPasswordAndEmail($post, $userID);
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
    private static function isTokenValid(int $userID, string $token, string $type): bool
    {
        return Tokens::check($userID, $token, $type);
    }

    /**
     * @throws Exception
     */
    public static function verifyEmail()
    {
        $token = HTTPQueryHandler::parseGetAndGetToken();
        if (empty($token)) {
            Html::render('404');
            exit;
        }
        $post = $_POST;
        try {
            HTTPQueryHandler::validateAndProcessPostWithPassword($post);
        } catch (ExceptionWithUserMessage $e) {
            Api::openPageWithUserMessage('/', $e->getMessage());
            exit;
        }
        try {
            $userID = self::signIn($post, false);
        } catch (ExceptionWithUserMessage $e) {
            Api::openPageWithUserMessage('/verify', $e->getMessage(), 'red', $token);
            exit;
        }
        if (self::isTokenValid($userID, $token, 'verify')) {
            Tokens::delete($token);
            Users::verifyEmail($userID);
            Api::openPageWithUserMessage("/profile/$userID", 'Email подтверждён', 'green');
        } else {
            Api::openPageWithUserMessage('/', 'Email не подтверждён');
        }
    }

    /**
     * @throws Exception
     */
    public static function requestRestore()
    {
        $post = $_POST;
        try {
            HTTPQueryHandler::validateAndProcessPost($post);
        } catch (ExceptionWithUserMessage $e) {
            Api::openPageWithUserMessage('/request-restore', $e->getMessage());
            exit;
        }
        $email = $post['email'];
        $userID = Users::getIdByEmail($email);
        if ($userID) {
            $token = self::getToken($userID, 'restore');
            EmailSender::send('restore', $userID, ['restoreUrl' => self::getUrlWithToken('restore', $token)]);
        }
        Api::openPageWithUserMessage(
            '/request-restore',
            "Если email $email зарегистрирован на сайте, на него придёт письмо c инструкциями",
            'green'
        );
    }

    /**
     * @throws Exception
     */
    public static function restorePassword()
    {
        $token = HTTPQueryHandler::parseGetAndGetToken();
        if (empty($token)) {
            Html::render('404');
            exit;
        }
        $post = $_POST;
        try {
            HTTPQueryHandler::validateAndProcessPostWithPassword($post, true);
        } catch (ExceptionWithUserMessage $e) {
            Api::openPageWithUserMessage('/request-restore', $e->getMessage());
            exit;
        }
        if ($post['passwordHash'] != $post['passwordHashAgain']) {
            Api::openPageWithUserMessage('/restore', 'Пароли не совпадают', 'red', $token);
            exit;
        }
        $userID = Users::getIdByEmail($post['email']);
        if ($userID) {
            if (self::isTokenValid($userID, $token, 'restore')) {
                Tokens::delete($token);
                Users::updatePassword($userID, $post['passwordHash']);
                Api::openPageWithUserMessage('/sign-in', 'Пароль изменён', 'green');
                exit;
            }
        }
        Api::openPageWithUserMessage('/request-restore', 'Пароль не изменён');
    }

    private static function getUrlWithToken(string $type, string $token): string
    {
        return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . "/$type?token=$token";
    }
}
