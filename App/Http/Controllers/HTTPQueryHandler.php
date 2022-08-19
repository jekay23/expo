<?php

namespace Expo\App\Http\Controllers;

use Exception;
use Expo\Config\ExceptionWithUserMessage;

class HTTPQueryHandler
{
    /**
     * @throws ExceptionWithUserMessage
     */
    public static function validateAndProcessPost(array &$post): bool
    {
        if (isset($post['email'])) {
            $post['email'] = trim($post['email']);
            if (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
                throw new ExceptionWithUserMessage('Пожалуйста, перепроверьте email');
            }
        }
        if (isset($post['name'])) {
            // for some reason regexp with just а-я doesn't work... so here's a work-around:
            if (!preg_match('/^[a-zA-Zа-пр-цч-яА-Я\d\s\-\p{Cyrillic}]+$/', $post['name'])) {
                throw new ExceptionWithUserMessage('Ваше имя должно содержать только буквы, цифры, дефисы и пробелы');
            }
            $post['name'] = trim(preg_replace('/\s+/', ' ', $post['name']));
            $post['name'] = trim(preg_replace('/-+/', ' ', $post['name']), '-');
        }
        if (isset($post['pronoun'])) {
            $pronouns = ['none', 'he', 'she'];
            if (!in_array($post['pronoun'], $pronouns)) {
                throw new ExceptionWithUserMessage(false, 'Неизвестное обращение');
            }
        }
        if (isset($post['bio'])) {
            $post['bio'] = htmlspecialchars($post['bio']);
        }
        if (isset($post['contact'])) {
            $post['contact'] = htmlspecialchars($post['contact']);
        }
        return true;
    }

    /**
     * @throws ExceptionWithUserMessage
     * @throws Exception
     */
    public static function validateAndProcessPostWithPassword(array &$post, bool $isPasswordTwice = false): bool
    {
        $hash = HashHandler::getHash('password', $post['password'], $post['email']);
        unset($post['password']);
        $post['passwordHash'] = $hash;
        if ($isPasswordTwice) {
            $hashAgain = HashHandler::getHash('password', $post['passwordAgain'], $post['email']);
            unset($post['passwordAgain']);
            $post['passwordHashAgain'] = $hashAgain;
        }
        return self::validateAndProcessPost($post);
    }

    public static function validateGet(array $uriQuery): bool
    {
        foreach ($uriQuery as $key => $value) {
            if (htmlspecialchars($key) != $key || htmlspecialchars($value) != $value) {
                return false;
            }
        }
        return true;
    }

    public static function parseGetAndGetToken(): string
    {
        $uriQuery = self::parseGet();
        return $uriQuery['token'] ?? '';
    }

    public static function validateAndParseGet(): array
    {
        $uriQuery = self::parseGet();
        return (self::validateGet($uriQuery) ? $uriQuery : []);
    }

    private static function parseGet(): array
    {
        $uriQuery = [];
        parse_str($_SERVER['QUERY_STRING'], $uriQuery);
        return $uriQuery;
    }
}
