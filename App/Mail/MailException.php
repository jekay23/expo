<?php

namespace Expo\App\Mail;

use Exception;

class MailException extends Exception
{
    public function errorMessage(): string
    {
        return '<strong>' . htmlspecialchars($this->getMessage(), ENT_COMPAT | ENT_HTML401) . "</strong><br />\n";
    }
}
