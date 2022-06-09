<?php

namespace Expo\Resources\Views;

class View
{
# in the future it will construct the whole page, but now it's quite primitive
    public function showView($requestView)
    {
        if ($requestView == 'frontpage') {
            require 'frontpageView.php';
        }
    }
}
