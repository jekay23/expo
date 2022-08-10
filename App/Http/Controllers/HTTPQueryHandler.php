<?php

namespace Expo\App\Http\Controllers;

class HTTPQueryHandler
{
    public static function processPOST(array &$post): array
    {
        if (isset($post['name'])) {
            // for some reason regexp with just а-я doesn't work... so here's a work-around:
            if (!preg_match('/^[a-zA-Zа-пр-цч-яА-Я\d\s\-\p{Cyrillic}]+$/', $post['name'])) {
                return [false, 'Ваше имя должно содержать только буквы, цифры, дефисы и пробелы'];
            }
            $post['name'] = trim(preg_replace('/\s+/', ' ', $post['name']));
            $post['name'] = trim(preg_replace('/-+/', ' ', $post['name']), '-');
        }
        if (isset($post['email'])) {
            $post['email'] = trim($post['email']);
            if (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
                return [false, 'Пожалуйста, перепроверьте email'];
            }
        }
        if (isset($post['pronoun'])) {
            $pronouns = ['none', 'he', 'she'];
            if (!in_array($post['pronoun'], $pronouns)) {
                return [false, 'Неизвестное обращение'];
            }
        }
        if (isset($post['bio'])) {
            $post['bio'] = htmlspecialchars($post['bio']);
        }
        if (isset($post['contact'])) {
            $post['contact'] = htmlspecialchars($post['contact']);
        }
        return [true, null];
    }

    public static function processGET(array $uriQuery): bool
    {
        foreach ($uriQuery as $key => $value) {
            if (htmlspecialchars($key) != $key || htmlspecialchars($value) != $value) {
                return false;
            }
        }
        return true;
    }
}
