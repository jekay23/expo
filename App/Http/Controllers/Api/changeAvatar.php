<?php

namespace Expo\App\Http\Controllers\Api;

use Expo\App\Http\Controllers\HashHandler;
use Expo\App\Models\QueryBuilder;

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
        QueryBuilder::updateAvatar($userID, $filename);
    }
} else {
    $messageEndings = [
        1 => 'Файл весит больше 6 МБ',
        2 => 'Файл весит больше 6 МБ',
        3 => 'Файл не загрузился полностью',
        4 => 'Файл не был загрузжен',
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
exit;
