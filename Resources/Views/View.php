<?php

namespace Expo\Resources\Views;

class View
{
    public static function requireTemplate(string $templateName, string $templateType = '', array $variables = null)
    {
        if ('' !== $templateType) {
            $templateName = $templateType . 's/Templates/' . $templateName;
        }
        if (isset($variables)) {
            extract($variables);
        }
        $templateName .= '.php';
        require $templateName;
    }
}
