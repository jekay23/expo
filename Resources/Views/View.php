<?php

/**
 * View class
 * In the future it will construct the whole page, but now it's quite primitive
 */

namespace Expo\Resources\Views;

class View
{
    private static $requests = array(
        'frontpage' => 'frontpageTemplate.php',
        'profile' => 'profilePageTemplate.php',
        'photo' => 'photoPageTemplate.php',
        'compilation' => 'compilationPageTemplate.php',
        'exhibition' => 'exhibitionPageTemplate.php',
        'signIn' => 'signInPageTemplate.php',
        'signUp' => 'signUpPageTemplate.php'
    );

    public static function showView($requestView)
    {
        foreach (self::$requests as $request => $template) {
            if ($requestView == $request) {
                require $template;
            }
        }
    }
}
