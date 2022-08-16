<?php

namespace Expo\Resources\Views;

class Title
{
    private static array $titles = [
        'frontpage' => 'Выставка фотографов мехмата',
        'profile' => 'Платон Антониу',
        'photo' => 'Фото',
        'compilation' => 'Подборка &quot;Лето в Академгородке&quot;',
        'exhibition' => 'Текущая выставка',
        'signIn' => 'Вход',
        'signUp' => 'Регистрация',
        '404' => 'Страница не найдена',
        '403' => 'Доступ запрещён',
        '503' => 'Сервис не отвечает',
        'upload' => 'Загрузка снимков',
        'editProfile' => 'Настройки профиля',
        'changeAvatar' => 'Смена аватара',
        'changePasswordEmail' => 'Смена пароля и email',
        'support' => 'Поддержка',
        'faq' => 'Частозадаваемые вопросы',
        'license' => 'Пользовательское соглашение',
        'verify' => 'Подтверждение email',
        'requestRestore' => 'Восстановление пароля',
        'restore' => 'Восстановление пароля'
    ];

    public static function get(string $requestTitle, string $override): string
    {
        if (!empty($override)) {
            $title = $override;
        } else {
            $title = self::$titles[$requestTitle];
        }
        if ($title != 'Выставка фотографов мехмата') {
            $title .= ' | Выставка фотографов мехмата';
        }
        return $title;
    }
}
