<?php

namespace Expo\Resources\Views\Pages;

use Expo\App\Models\DataBaseConnection;
use Expo\App\Http\Controllers;

class Front
{
    public static function render()
    {
        DataBaseConnection::open();

        Controllers\Components\ExhibitionSlider::assemble('Текущая выставка', 'compilation', 10, 1);

        Controllers\Components\PhotoDisplay::assemble('slider', 'Лучшие фото месяца', 'best', 10);

        Controllers\Components\PhotoDisplay::assemble('grid', 'Последние опубликованные', 'latest', 12);

        Controllers\Components\PhotoDisplay::assemble('slider', 'Предыдущая выставка', 'latest', 10);

        Controllers\Components\TextSlider::assemble('Фотографы мехмата', 'latest', 10);

        Controllers\Components\PhotoDisplay::assemble('carousel', 'Подборка команды мехмата', 'compilation', 5, 2);

        Controllers\Components\TextSlider::assemble('Фильтры', 'filters', 3);
    }
}
