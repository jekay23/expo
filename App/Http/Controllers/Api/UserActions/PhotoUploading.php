<?php

namespace Expo\App\Http\Controllers\Api\UserActions;

use Exception;
use Expo\App\Http\Controllers\Api;
use Expo\App\Http\Controllers\Authentication;
use Expo\App\Http\Controllers\HashHandler;
use Expo\App\Models\Entities\Photos;
use Expo\App\Models\Entities\Users;

use const Expo\Pub\__ROOT__;

class PhotoUploading
{
    /**
     * @throws Exception
     */
    public static function changeAvatar()
    {
        $userID = Authentication::getUserIdFromCookie();
        if ('' === $_FILES['file']['name']) {
            $message = 'Вы не выбрали ни одного фото';
            Api::openPageWithUserMessage("/profile/$userID/change-avatar", $message);
            exit;
        }
        $file = $_FILES['file'];
        if (0 === $file['error']) {
            $time = time();
            $filename = self::appendExtensionToFilename(HashHandler::getHash('filename', "$time/"), $file['type']);
            while (file_exists(__ROOT__ . '/Public/uploads/photos/' . $filename)) {
                $extraSymbol = rand(0, 9);
                $filename = substr_replace($filename, "$extraSymbol", -5, 0);
            }
            $moveStatus = move_uploaded_file(
                $file['tmp_name'],
                __ROOT__ . '/Public/uploads/photos/' . $filename
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
            Api::openPageWithUserMessage("/profile/$userID/change-avatar", $message);
            exit;
        }
        $message = 'Аватар обновлён';
        Api::openPageWithUserMessage("/photo/$userID", $message, 'green');
    }

    /**
     * @throws Exception
     */
    public static function upload()
    {
        $numOfFiles = count($_FILES['files']['name']);
        if (0 === $numOfFiles || (1 === $numOfFiles && '' === $_FILES['files']['name'][0])) {
            $message = 'Вы не выбрали ни одного фото';
            Api::openPageWithUserMessage('/upload', $message);
            exit;
        }
        $files = $_FILES['files'];
        $userID = Authentication::getUserIdFromCookie();
        for ($i = 0; $i < $numOfFiles; $i++) {
            if (0 === $files['error'][$i]) {
                $time = time();
                $filename = self::appendExtensionToFilename(
                    HashHandler::getHash('filename', "$time/" . "$i"),
                    $files['type'][$i]
                );
                // TODO: remove code duplication (here below and in changeAvatar()
                while (file_exists(__ROOT__ . '/Public/uploads/photos/' . $filename)) {
                    $extraSymbol = rand(0, 9);
                    $filename = substr_replace($filename, "$extraSymbol", -5, 0);
                }
                $moveStatus = move_uploaded_file(
                    $files['tmp_name'][$i],
                    __ROOT__ . '/Public/uploads/photos/' . $filename
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
                Api::openPageWithUserMessage('/upload', $message);
                exit;
            }
        }
        $message = $i . ' фото было добавлено';
        Api::openPageWithUserMessage("/profile/$userID", $message, 'green');
    }

    private static function appendExtensionToFilename(string $filename, string $type): string
    {
        $filenameExtensions = [
            'image/jpeg' => '.jpg',
            'image/png' => '.png'
        ];
        return $filename . $filenameExtensions[$type];
    }
}
