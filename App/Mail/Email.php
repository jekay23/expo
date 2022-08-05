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
        $this->body = '';
        if (isset($this->recipientName)) {
            $this->body .= "<p><b>Здравствуйте, $this->recipientName!</b></p>";
        } else {
            $this->body .= '<p><b>Здравствуйте!</b></p>';
        }
        $this->body .= '<br>';
        switch ($this->type) {
            case 'verify':
                $verifyUrl = $customStings['verifyUrl'];
                $recipientEmail = $this->recipientEmails[0];
                $this->body .= '<p>Вы только что зарегистрировались на сайте ';
                $this->body .= '<a href="http://62.113.110.35/">Выставки фотографов мехмата</a>.</p>';
                $this->body .= "<p>Пожалуйста, подтвердите ваш адрес электронной почты <b>$recipientEmail</b>, ";
                $this->body .= "перейдя по одноразовой ссылке <a href=\"$verifyUrl\">$verifyUrl</a>.</p><br>";
                $this->body .= '<p>Пожалуйста, <b>никому не сообщайте данную ссылку</b>.</p><br>';
                $this->body .= '<p>Если вы не регистрировались на сайте, вы можете проигнорировать это письмо.</p><br>';
                $this->body .= '<br><p>C уважением, <br>Команда мехмата</p>';
                break;
            case 'exhibit':
                $photoUrl = $customStings['photoUrl'];
                $exhibitUrl = $customStings['exhibitUrl'];
                $this->body .= '<p>Ваша фотография на сайте ';
                $this->body .= '<a href="http://62.113.110.35/">Выставки фотографов мехмата</a> ';
                $this->body .= 'была отобрана для участия в следующей выставке!</p><br>';
                $this->body .= '<p>Подробности:</p>';
                $this->body .= "<ul><li>фотография: <a href=\"$photoUrl\">$photoUrl</a></li>";
                $this->body .= "<li>выставка: <a href=\"$exhibitUrl\">$exhibitUrl</a></li></ul>";
                $this->body .= '<br><br>';
                $this->body .= '<p>C уважением, <br>Команда мехмата</p>';
                break;
            case 'restore':
                $restoreUrl = $customStings['restoreUrl'];
                $this->body .= '<p>Нам только что поступил запрос на восстновление пароля от вашего аккаунта ';
                $this->body .= 'на сайте <a href="http://62.113.110.35/">Выставки фотографов мехмата</a>.</p><br>';
                $this->body .= "<p>Если это были вы, пожалуйста, перейдите по одноразовой ссылке ";
                $this->body .= "<a href=\"$restoreUrl\">$restoreUrl</a> ";
                $this->body .= 'для восстановления пароля. <b>Никому не сообщайте данную ссылку</b>.</p><br>';
                $this->body .= '<p>Если это были не вы, пожалуйста, проигнорируйте это письмо. ';
                $this->body .= 'Ваш аккаунт в безопасности.</p><br><br>';
                $this->body .= '<p>C уважением, <br>Команда мехмата</p>';
                break;
            case 'custom':
                $this->body .= $customStings;
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
                throw new Exception('No recipient set for email');
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
        } catch (Exception $e) {
            $result = 'error';
            $status = "Сообщение не было отправлено. Причина ошибки: $email->ErrorInfo";
        }

        echo json_encode(["result" => $result, "status" => $status]);
    }
}
