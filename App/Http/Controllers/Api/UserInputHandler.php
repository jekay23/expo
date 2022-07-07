<?php

namespace Expo\App\Http\Controllers\Api;

class UserInputHandler
{
    public static function processPost(array &$post): array
    {
        if (isset($post['name'])) {
            if (!preg_match('/^[a-zA-Zа-пр-цч-яА-Я\d\s\-\p{Cyrillic}]+$/', $post['name'])) {
                return [false, 'Name should contain only letters, numbers, hyphens and spaces.'];
            }
            $post['name'] = trim(preg_replace('/\s+/', ' ', $post['name']));
            $post['name'] = trim(preg_replace('/-+/', ' ', $post['name']), '-');
        }
        if (isset($post['email'])) {
            $post['email'] = trim($post['email']);
            if (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
                return [false, 'Your email is incorrect'];
            }
        }
        if (isset($post['pronoun'])) {
            $pronouns = ['none', 'he', 'she'];
            if (!in_array($post['pronoun'], $pronouns)) {
                return [false, 'Pronoun is unknown.'];
            }
        }
        return [true, null];
    }

    public static function processUriQuery(array $uriQuery): bool
    {
        foreach ($uriQuery as $key => $value) {
            if (htmlspecialchars($key) != $key || htmlspecialchars($value) != $value) {
                return false;
            }
        }
        return true;
    }
}
