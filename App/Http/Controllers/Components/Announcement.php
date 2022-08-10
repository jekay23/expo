<?php

namespace Expo\App\Http\Controllers\Components;

use Expo\App\Http\Controllers\HTTPQueryHandler;
use Expo\Resources\Views\View;

class Announcement
{
    public static function render()
    {
        if (!empty($_SERVER['QUERY_STRING'])) {
            $uriQuery = [];
            parse_str($_SERVER['QUERY_STRING'], $uriQuery);
            $validUriQuery = HTTPQueryHandler::processGET($uriQuery);
            if ($validUriQuery) {
                $message = '';
                $bgClass = 'bg-info';
                if (isset($uriQuery['message'])) {
                    $message = $uriQuery['message'];
                }
                if (isset($uriQuery['color'])) {
                    switch ($uriQuery['color']) {
                        case 'red':
                            $bgClass = 'bg-warning';
                            break;
                        case 'green':
                            $bgClass = 'bg-success';
                            break;
                    }
                }
                View::requireTemplate('announcementBar', 'Component', compact('message', 'bgClass'));
            }
        }
    }
}