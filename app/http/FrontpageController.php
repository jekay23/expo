<?php

/**
 * Front page controller
 * Manages the front page, which is accessed by URL 'IP/'.
 */

namespace App\Http;

use Resources\Views\View;

class FrontpageController
{
    public function openFrontpage()
    {
        $view = new View();
        $view->showView('frontpage');
    }
}
