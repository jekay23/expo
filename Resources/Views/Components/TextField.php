<?php

namespace Expo\Resources\Views\Components;

use Expo\Resources\Views\View;

class TextField
{
    public static function render(string $pageType, string $textFieldLabel, array $inputAttributes = null)
    {
        $inputAttributeString = self::generateInputAttributesString($inputAttributes);
        if ('Пароль' === $textFieldLabel) {
            if (!isset($inputAttributes['type']) || 'password' != $inputAttributes['type']) {
                $inputAttributeString .= ' type="password"';
            }
        }
        switch ($pageType) {
            case 'editProfile':
                $colSmSize = 2;
                View::requireTemplate(
                    'profileTextField',
                    'Component',
                    compact('colSmSize', 'textFieldLabel', 'inputAttributeString')
                );
                break;
            case 'changePasswordEmail':
                $colSmSize = 3;
                View::requireTemplate(
                    'profileTextField',
                    'Component',
                    compact('colSmSize', 'textFieldLabel', 'inputAttributeString')
                );
                break;
            case 'signIn':
                View::requireTemplate(
                    'signInTextField',
                    'Component',
                    compact('textFieldLabel', 'inputAttributeString')
                );
        }
    }

    public static function generateInputAttributesString($inputAttributes): string
    {
        $inputAttributeString = '';
        if (isset($inputAttributes)) {
            foreach ($inputAttributes as $attribute => $value) {
                if (true === $value) {
                    $inputAttributeString .= " $attribute";
                } else {
                    $inputAttributeString .= " $attribute=\"$value\"";
                }
            }
        }
        return $inputAttributeString;
    }
}
