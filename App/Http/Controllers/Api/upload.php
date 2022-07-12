<?php

namespace Expo\App\Http\Controllers\Api;

use Expo\App\Http\Controllers\HashHandler;
use Expo\App\Models\QueryBuilder;

$numOfFiles = count($_FILES['files']['name']);
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
            QueryBuilder::addPhoto($userID, $filename);
        }
    } else {
        $messageEndings = [
            1 => '-й файл весит больше 6 МБ',
            2 => '-й файл весит больше 6 МБ',
            3 => '-й файл не загрузился полностью',
            4 => '-й файл не был загрузжен',
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
exit;
