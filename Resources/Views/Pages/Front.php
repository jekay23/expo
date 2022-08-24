<?php

namespace Expo\Resources\Views\Pages;

use Expo\App\Http\Controllers;
use Expo\Resources\Views\Html;

class Front
{
    public static function render()
    {
        try {
            Controllers\Components\ExhibitionSlider::prepare('Текущая выставка', 'compilation', 10, 1);
            Controllers\Components\PhotoDisplay::prepare('slider', 'Лучшие фото месяца', 'best', 10);
            Controllers\Components\PhotoDisplay::prepare('grid', 'Последние опубликованные', 'latest', 12);
            Controllers\Components\PhotoDisplay::prepare('slider', 'Предыдущая выставка', 'latest', 10);
            Controllers\Components\TextSlider::prepare('Фотографы мехмата', 'latest', 20);
            Controllers\Components\PhotoDisplay::prepare('carousel', 'Подборка команды мехмата', 'compilation', 5, 2);
            Controllers\Components\TextSlider::prepare('Фильтры', 'filters', 3);
        } catch (\Exception $e) {
            Html::render('503');
        }
    }
}
