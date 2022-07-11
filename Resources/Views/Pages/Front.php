<?php

namespace Expo\Resources\Views\Pages;

use Expo\App\Models\DataBaseConnection;
use Expo\App\Http\Controllers;

class Front
{
    public static function render()
    {
        DataBaseConnection::open();

        Controllers\Components\ExhibitionSlider::prepare('Текущая выставка', 'compilation', 10, 1);

        Controllers\Components\PhotoDisplay::prepare('slider', 'Лучшие фото месяца', 'best', 10);

        Controllers\Components\PhotoDisplay::prepare('grid', 'Последние опубликованные', 'latest', 12);

        Controllers\Components\PhotoDisplay::prepare('slider', 'Предыдущая выставка', 'latest', 10);

        Controllers\Components\TextSlider::prepare('Фотографы мехмата', 'latest', 10);

        Controllers\Components\PhotoDisplay::prepare('carousel', 'Подборка команды мехмата', 'compilation', 5, 2);

        Controllers\Components\TextSlider::prepare('Фильтры', 'filters', 3);
    }
}
