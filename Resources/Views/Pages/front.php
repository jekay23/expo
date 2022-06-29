<?php

use Expo\App\Models\DataBaseConnection;
use Expo\App\Http\Controllers;

DataBaseConnection::open();

Controllers\Components\ExhibitionSlider::assemble('Текущая выставка', 'compilation', 10, 1);

Controllers\Components\ContinuousSlider::assemble('Лучшие фото месяца', 'best', 10);

Controllers\Components\SmallGrid::assemble('Последние опубликованные', 'latest', 12);

Controllers\Components\ContinuousSlider::assemble('Предыдущая выставка', 'latest', 10);

Controllers\Components\TextSlider::assemble('Фотографы мехмата', 'latest', 10);

Controllers\Components\Carousel::assemble('Подборка команды мехмата', 'compilation', 5, 2);

Controllers\Components\TextSlider::assemble('Фильтры', 'filters', 3);

DataBaseConnection::close();
