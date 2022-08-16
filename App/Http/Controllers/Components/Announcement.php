<?php

namespace Expo\App\Http\Controllers\Components;

use Expo\App\Http\Controllers\HTTPQueryHandler;
use Expo\Resources\Views\View;

class Announcement
{
    private static array $bgClasses = [
        'red' => 'bg-warning',
        'green' => 'bg-success'
    ];

    public static function render()
    {
        if (!empty($_SERVER['QUERY_STRING'])) {
            $uriQuery = [];
            parse_str($_SERVER['QUERY_STRING'], $uriQuery);
            $validUriQuery = HTTPQueryHandler::validateGet($uriQuery);
            if ($validUriQuery) {
                $message = '';
                if (isset($uriQuery['message'])) {
                    $message = $uriQuery['message'];
                }
                if (isset($uriQuery['color'])) {
                    $bgClass = self::$bgClasses[$uriQuery['color']] ?? 'bg-info';
                } else {
                    $bgClass = 'bg-info';
                }
                View::requireTemplate('announcementBar', 'Component', compact('message', 'bgClass'));
            }
        }
    }
}
