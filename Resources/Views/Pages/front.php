<?php

\Expo\App\Models\DataBaseConnection::open();

\Expo\App\Http\Controllers\Components\ExhibitionSlider::assemble('Текущая выставка', 1, 10);

\Expo\App\Http\Controllers\Components\ContinuousSlider::assemble('Лучшие фото месяца', 'best', 10);

\Expo\App\Http\Controllers\Components\SmallGrid::assemble('Последние опубликованные', 'latest', 12);

\Expo\App\Http\Controllers\Components\ContinuousSlider::assemble('Предыдущая выставка', 'latest', 10);

\Expo\App\Http\Controllers\Components\TextSlider::assemble('Фотографы мехмата', 'latest', 10);

\Expo\App\Http\Controllers\Components\Carousel::assemble('Подборка команды мехмата', 2, 5);

\Expo\App\Http\Controllers\Components\TextSlider::assemble('Фильтры', 'filters', 3);

\Expo\App\Models\DataBaseConnection::close();
