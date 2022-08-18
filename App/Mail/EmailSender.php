<?php

namespace Expo\App\Mail;

use Exception;
use Expo\App\Models\Entities\Users;

class EmailSender
{
    /**
     * @param string $type 'verify' | 'exhibit' | 'restore' | 'custom'
     * @param int $id
     * @param array $customStrings
     * @return void
     * @throws Exception
     */
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
