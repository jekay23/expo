<?php

namespace Expo\App\Mail;

use Expo\App\Models\Entities\Users;

class EmailSender
{
    public static function send(string $type, int $id, array $customStrings)
    {
        $email = new Email($type);
        $user = Users::getUserData($id, true);
        $email->addRecipient($user['email']);
        $email->setRecipientName($user['name']);
        $email->setBody($customStrings);
        $email->send();
    }
}
