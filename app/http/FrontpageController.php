<?php

/**
 * Front page controller
 * Manages the front page, which is accessed by URL 'IP/'.
 */

namespace Expo\App\Http;

use Expo\Resources\Views\View;

class FrontpageController
{
    public function openFrontpage()
    {
        require __DIR__ . '/../../resources/views/View.php';
        $view = new View();
        $view->showView('frontpage');
    }
}
