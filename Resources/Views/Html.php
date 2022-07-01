<?php

namespace Expo\Resources\Views;

use Expo\App\Models\DataBaseConnection;

class Html
{
    public static function requireDynamic(string $title, string $templateClass, string $currentNavbarLink = null)
    {
        $stickFooter = false;
        $templateClass = 'Expo\\Resources\\Views\\Pages\\' . $templateClass;
        $page = null;
        require 'html.php';
    }

    public static function requireStatic(string $title, string $page, string $currentNavbarLink = null)
    {
        $stickFooter = false;
        $templateClass = null;
        $page = 'Pages/Templates/' . $page . '.php';
        require 'html.php';
    }

    public static function render($templateClass, $page, bool &$stickFooter)
    {
        if (isset($templateClass)) {
            $templateClass::render($stickFooter);
        } elseif (isset($page)) {
            require $page;
        }
        DataBaseConnection::close();
    }
}
