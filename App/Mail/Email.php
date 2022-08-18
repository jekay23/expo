<?php

namespace Expo\App\Mail;

class Email
{
    private string $type;
    private string $title;
    private string $body;
    private array $recipientEmails;
    private string $recipientName;

    private static array $types = ['verify', 'exhibit', 'restore', 'custom'];

    private static array $titles = [
        'verify' => 'Подтверждение email — Выставка фотографов мехмата',
        'exhibit' => 'Отбор на выставку фотографов мехмата',
        'restore' => 'Восстановление пароля — Выставка фотографов мехмата'
    ];

    /**
     * @param string $type 'verify' | 'exhibit' | 'restore' | 'custom'
     */
    public function __construct(string $type)
    {
        if (in_array($type, self::$types)) {
            $this->type = $type;
            $this->title = self::$titles[$type];
        }
        $this->recipientEmails = [];
    }

    public function setNewTitle(string $title): bool
    {
        $this->title = $title;
        return true;
    }

    public function addRecipient(string $email): bool
    {
        $this->recipientEmails[] = $email;
        return true;
    }

    public function setRecipientName(string $name): bool
    {
        $this->recipientName = $name;
        return true;
    }

    public function setBody(array $customStings): bool
    {
        if (isset($this->recipientName)) {
            $this->body = "<p><b>Здравствуйте, $this->recipientName!</b></p>";
        } else {
            $this->body = '<p><b>Здравствуйте!</b></p>';
        }
        $this->body .= '<br>';
        switch ($this->type) {
            case 'verify':
                ob_start();
                $verifyUrl = $customStings['verifyUrl'];
                $recipientEmail = $this->recipientEmails[0];
                require 'Bodies/verify.php';
                $this->body .= ob_get_clean();
                break;
            case 'exhibit':
                ob_start();
                $photoUrl = $customStings['photoUrl'];
                $exhibitUrl = $customStings['exhibitUrl'];
                require 'Bodies/exhibit.php';
                $this->body .= ob_get_clean();
                break;
            case 'restore':
                ob_start();
                $restoreUrl = $customStings['restoreUrl'];
                require 'Bodies/restore.php';
                $this->body .= ob_get_clean();
                break;
            case 'custom':
                $this->body .= $customStings[0];
        }
        return true;
    }

    public function send()
    {
        $email = new PHPMailer();
        try {
            $email->isSMTP();
            $email->CharSet = 'UTF-8';
            $email->SMTPAuth = true;
            //$mail->SMTPDebug = 2;
            $email->Debugoutput = function ($str) {
                $GLOBALS['status'][] = $str;
            };

            list($email->Username, $email->Password, $senderEmail) = EmailCredentials::getEmailCredentials();
            $email->Host = 'smtp.yandex.ru';
            $email->SMTPSecure = 'ssl';
            $email->Port = 465;
            $email->setFrom($senderEmail, 'Выставка фотографов мехмата');

            if (1 == count($this->recipientEmails) && isset($this->recipientName)) {
                $email->addAddress($this->recipientEmails[0], $this->recipientName);
            } elseif (count($this->recipientEmails) > 1) {
                foreach ($this->recipientEmails as $recipientEmail) {
                    $email->addAddress($recipientEmail);
                }
            } else {
                throw new MailException('No recipient set for email');
            }

            $email->isHTML(true);
            $email->Subject = $this->title;
            $email->Body = $this->body;

            $status = '';
            if ($email->send()) {
                $result = 'success';
            } else {
                $result = 'error';
            }
        } catch (MailException $e) {
            $result = 'error';
            $status = "Сообщение не было отправлено. Причина ошибки: $email->ErrorInfo";
        }

        echo json_encode(["result" => $result, "status" => $status]);
    }
}
