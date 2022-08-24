<?php

namespace Expo\App\Http\Controllers\Pages;

use Expo\Resources\Views\Html;

class PagesWithNoPreparation
{
    // Classes that use open() are not just unified because in the theoretical future all pages
    // will need to be prepared, thus will need their controller-classes back

    private static array $pageNames = ['requestRestore', 'license', 'support', 'front', 'faq'];

    /**
     * @param array $requestList
     * @param string $pageName 'requestRestore' | 'license' | 'support' | 'front' | 'faq'
     * @return void
     */
    public static function open(array $requestList, string $pageName)
    {
        if (!empty($requestList) || !in_array($pageName, self::$pageNames)) {
            Html::render('404');
        } else {
            Html::render($pageName);
        }
    }
}
