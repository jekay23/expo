<?php

namespace Expo\App\Http\Controllers\Api;

use Expo\App\Http\Controllers\Authentication;
use Expo\App\Http\Controllers\HashHandler;
use Expo\App\Http\Controllers\HTTPQueryHandler;
use Expo\App\Models\Entities\Likes;
use Expo\App\Models\Entities\Photos;
use Expo\App\Models\Entities\Users;
use Expo\Resources\Views\View;

class UserActions
{
    public static function addLike()
    {
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
        $validUriQuery = HTTPQueryHandler::processGET($uriQuery);
        if ($validUriQuery && isset($uriQuery['photoID'])) {
            $photoID = $uriQuery['photoID'];
            $likeSet = Likes::checkLike($userID, $photoID);
            if ($likeSet) {
                $uriQuery = http_build_query(['message' => 'Вы уже оценивали это фото', 'color' => 'red']);
                header("Location: /photo/$photoID?$uriQuery");
            } else {
                Likes::addLike($userID, $photoID);
                header("Location: /photo/$photoID");
            }
        } else {
            header("Location: /");
        }
    }

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

    public static function editProfile()
    {
        $post = $_POST;
        $userID = \Expo\App\Http\Controllers\Authentication::getUserIdFromCookie();
        list($postStatus, $error) = \Expo\App\Http\Controllers\HTTPQueryHandler::processPOST($post);
        if (!$postStatus) {
            $uriQuery = http_build_query(['message' => $error, 'color' => 'red']);
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
        \Expo\App\Models\Entities\Users::updateProfileData($user);
        $uriQuery = http_build_query(['message' => 'Данные профиля обновлены', 'color' => 'green']);
        header("Location: /profile/$userID?$uriQuery");
    }

    public static function removeLike()
    {
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
        $validUriQuery = HTTPQueryHandler::processGET($uriQuery);
        if ($validUriQuery && isset($uriQuery['photoID'])) {
            $photoID = $uriQuery['photoID'];
            $likeSet = Likes::checkLike($userID, $photoID);
            if (!$likeSet) {
                $uriQuery = http_build_query(['message' => 'Вы не оценивали это фото', 'color' => 'red']);
                header("Location: /photo/$photoID?$uriQuery");
            } else {
                Likes::removeLike($userID, $photoID);
                header("Location: /photo/$photoID");
            }
        } else {
            header("Location: /");
        }
    }

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
                switch ($files['type'][$i]) {
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
        if (HTTPQueryHandler::processGET($uriQuery)) {
            return $uriQuery;
        } else {
            return [];
        }
    }
}
