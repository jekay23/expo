<?php

namespace Expo\Resources\Views\Components;

use Expo\Resources\Views\View;

class TextField
{
    public static function render(string $textFieldLabel, array $inputAttributes = null)
    {
        $inputAttributeString = '';
        if (isset($inputAttributes)) {
            foreach ($inputAttributes as $attribute => $value) {
                $inputAttributeString .= " $attribute=\"$value\"";
            }
        }
        if ('Пароль' === $textFieldLabel) {
            if (!isset($inputAttributes['type']) && 'password' != $inputAttributes['type']) {
                $inputAttributeString .= ' type="password"';
            }
        }
        View::requireTemplate('textField', 'Component', compact('textFieldLabel', 'inputAttributeString'));
    }
}