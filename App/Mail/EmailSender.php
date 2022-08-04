<?php

namespace Expo\App\Mail;

use Expo\App\Models\Users;

class EmailSender
{
    public static function send(string $type, int $id)
    {
        $email = new Email($type);
        $user = Users::getUserData($id, true);
        $email->addRecipient($user['email']);
        $email->setRecipientName($user['name']);
        $email->setBody();
        $email->send();
    }
}
