<?php

namespace Expo\App\Mail;

use Exception;
use Expo\App\Http\Controllers\Api\AdminActions\Authorizer;
use Expo\App\Http\Controllers\HTTPQueryHandler;
use Expo\App\Models\Entities\Users;
use Expo\Resources\Views\View;

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

    /**
     * @throws Exception
     */
    public static function sendTestEmail()
    {
        $uriQuery = HTTPQueryHandler::validateAndParseGet();
        if (isset($uriQuery['type']) && isset($uriQuery['userID'])) {
            Authorizer::callIfUserIsEditor(function ($uriQuery) {
                EmailSender::send($uriQuery['type'], $uriQuery['userID'], ['This is a test email']);
            }, $uriQuery);
        } else {
            View::render('404');
        }
    }
}
