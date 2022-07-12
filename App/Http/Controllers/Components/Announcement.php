<?php

namespace Expo\App\Http\Controllers\Components;

use Expo\App\Http\Controllers\Api\UserInputHandler;
use Expo\Resources\Views\View;

class Announcement
{
    public static function render()
    {
        if (!empty($_SERVER['QUERY_STRING'])) {
            $uriQuery = [];
            parse_str($_SERVER['QUERY_STRING'], $uriQuery);
            $validUriQuery = UserInputHandler::processUriQuery($uriQuery);
            if ($validUriQuery) {
                $message = '';
                $color = '#939292';
                if (isset($uriQuery['message'])) {
                    $message = $uriQuery['message'];
                }
                if (isset($uriQuery['color'])) {
                    switch ($uriQuery['color']) {
                        case 'red':
                            $color = '#ff8f81';
                            break;
                        case 'green':
                            $color = '#BCDDCD';
                            break;
                    }
                }
                View::requireTemplate('announcementBar', 'Component', compact('message', 'color'));
            }
        }
    }
}