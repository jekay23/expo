<?php

namespace Expo\App\Http\Controllers\Api;

use Exception;
use Expo\App\Http\Controllers\Authentication;
use Expo\App\Http\Controllers\HashHandler;
use Expo\App\Http\Controllers\HTTPQueryHandler;
use Expo\App\Models\Entities\Likes;
use Expo\App\Models\Entities\Photos;
use Expo\App\Models\Entities\Users;
use Expo\Config\ExceptionWithUserMessage;
use Expo\Resources\Views\View;

class UserActions
{
    /**
     * @throws Exception
     */
    public static function toggleLike(string $type)
    {
        $types = ['like', 'dislike'];
        if (!in_array($type, $types)) {
            throw new Exception('Unknown like toggle type');
        }
        $likeShouldBeSet = ('like' == $type);
        $userID = Authentication::getUserIdFromCookie();
        if (0 == $userID) {
            $uriQuery = http_build_query(['message' => 'Войдите в профиль, чтобы оценивать фото', 'color' => 'red']);
            header("Location: /sign-in?$uriQuery");
            exit;
        }
        if (empty($_SERVER['QUERY_STRING'])) {
            header("Location: /");
            exit;
        }
        $uriQuery = [];
        parse_str($_SERVER['QUERY_STRING'], $uriQuery);
        $validUriQuery = HTTPQueryHandler::validateGet($uriQuery);
        if ($validUriQuery && isset($uriQuery['photoID'])) {
            $photoID = $uriQuery['photoID'];
            $isLikeSet = Likes::checkLike($userID, $photoID);
            if ($isLikeSet == $likeShouldBeSet) {
                $uriQuery = http_build_query(['message' => 'Вы уже оценивали это фото', 'color' => 'red']);
                header("Location: /photo/$photoID?$uriQuery");
            } else {
                $likeShouldBeSet ? Likes::addLike($userID, $photoID) : Likes::removeLike($userID, $photoID);
                header("Location: /photo/$photoID");
            }
        } else {
            header("Location: /");
        }
    }

    /**
     * @throws Exception
     */
    public static function changeAvatar()
    {
        $userID = Authentication::getUserIdFromCookie();
        if ('' === $_FILES['file']['name']) {
            $message = 'Вы не выбрали ни одного фото';
            $uriQuery = http_build_query(['message' => $message, 'color' => 'red']);
            header("Location: /profile/$userID/change-avatar?$uriQuery");
            exit;
        }
        $file = $_FILES['file'];
        if (0 === $file['error']) {
            $time = time();
            $filename = HashHandler::getHash('filename', "$time/");
            switch ($file['type']) {
                case 'image/jpeg':
                    $filename .= '.jpg';
                    break;
                case 'image/png':
                    $filename .= '.png';
                    break;
            }
            while (file_exists(__DIR__ . '/../../../../Public/uploads/photos/' . $filename)) {
                $extraSymbol = rand(0, 9);
                $filename = substr_replace($filename, "$extraSymbol", -5, 0);
            }
            $moveStatus = move_uploaded_file(
                $file['tmp_name'],
                __DIR__ . '/../../../../Public/uploads/photos/' . $filename
            );
            if ($moveStatus) {
                Users::updateAvatar($userID, $filename);
            }
        } else {
            $messageEndings = [
                1 => 'Файл весит больше 6 МБ',
                2 => 'Файл весит больше 6 МБ',
                3 => 'Файл не загрузился полностью',
                4 => 'Файл не был загружен',
                6 => 'Файл не был помещён во временную папку на сервере'
            ];
            $message = $messageEndings[$file['error']];
            $uriQuery = http_build_query(['message' => $message, 'color' => 'red']);
            header("Location: /profile/$userID/change-avatar?$uriQuery");
            exit;
        }
        $message = 'Аватар обновлён';
        $uriQuery = http_build_query(['message' => $message, 'color' => 'green']);
        header("Location: /profile/$userID?$uriQuery");
    }

    /**
     * @throws Exception
     */
    public static function editProfile()
    {
        $post = $_POST;
        $userID = Authentication::getUserIdFromCookie();
        try {
            HTTPQueryHandler::validateAndProcessPost($post);
        } catch (ExceptionWithUserMessage $e) {
            $uriQuery = http_build_query(['message' => $e->getMessage(), 'color' => 'red']);
            header("Location: /profile/$userID/edit?$uriQuery");
            exit;
        }
        $user = [
            'userID' => $userID,
            'name' => $post['name'],
            'pronoun' => $post['pronoun'],
            'bio' => $post['bio'],
            'contact' => $post['contact']
        ];
        Users::updateProfileData($user);
        $uriQuery = http_build_query(['message' => 'Данные профиля обновлены', 'color' => 'green']);
        header("Location: /profile/$userID?$uriQuery");
    }

    /**
     * @throws Exception
     */
    public static function upload()
    {
        $numOfFiles = count($_FILES['files']['name']);
        if (0 === $numOfFiles || (1 === $numOfFiles && '' === $_FILES['files']['name'][0])) {
            $message = 'Вы не выбрали ни одного фото';
            $uriQuery = http_build_query(['message' => $message, 'color' => 'red']);
            header("Location: /upload?$uriQuery");
            exit;
        }
        $files = $_FILES['files'];
        $userID = Authentication::getUserIdFromCookie();
        for ($i = 0; $i < $numOfFiles; $i++) {
            if (0 === $files['error'][$i]) {
                $time = time();
                $filename = HashHandler::getHash('filename', "$time/" . "$i");
                $filenameExtensions = [
                    'image/jpeg' => '.jpg',
                    'image/png' => '.png'
                ];
                $filename .= $filenameExtensions[$files['type'][$i]];
                while (file_exists(__DIR__ . '/../../../../Public/uploads/photos/' . $filename)) {
                    $extraSymbol = rand(0, 9);
                    $filename = substr_replace($filename, "$extraSymbol", -5, 0);
                }
                $moveStatus = move_uploaded_file(
                    $files['tmp_name'][$i],
                    __DIR__ . '/../../../../Public/uploads/photos/' . $filename
                );
                if ($moveStatus) {
                    Photos::addPhoto($userID, $filename);
                }
            } else {
                $messageEndings = [
                    1 => '-й файл весит больше 6 МБ',
                    2 => '-й файл весит больше 6 МБ',
                    3 => '-й файл не загрузился полностью',
                    4 => '-й файл не был загружен',
                    6 => '-й файл не был помещён во временную папку на сервере'
                ];
                $message = $i . ' фото было добавлено, однако ' . ($i + 1) . $messageEndings[$files['error'][$i]];
                $uriQuery = http_build_query(['message' => $message, 'color' => 'red']);
                header("Location: /upload?$uriQuery");
                exit;
            }
        }
        $message = $i . ' фото было добавлено';
        $uriQuery = http_build_query(['message' => $message, 'color' => 'green']);
        header("Location: /profile/$userID?$uriQuery");
    }

    /**
     * @throws Exception
     */
    public static function quickAction(string $type)
    {
        $uriQuery = self::getUriQueryArray();
        if (isset($uriQuery['name'])) {
            $userID = Authentication::getUserIdFromCookie();
            if ($userID) {
                switch ($type) {
                    case 'like':
                        Likes::addLikeByName($userID, $uriQuery['name']);
                        break;
                    case 'dislike':
                        Likes::removeLikeByName($userID, $uriQuery['name']);
                        break;
                }
            }
        } else {
            View::render('404');
        }
    }

    private static function getUriQueryArray(): array
    {
        $uriQuery = [];
        parse_str($_SERVER['QUERY_STRING'], $uriQuery);
        if (HTTPQueryHandler::validateGet($uriQuery)) {
            return $uriQuery;
        } else {
            return [];
        }
    }
}
