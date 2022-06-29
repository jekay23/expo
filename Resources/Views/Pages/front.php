<?php

\Expo\App\Models\DataBaseConnection::open();

\Expo\App\Http\Controllers\Components\ExhibitionSlider::renderComponent('Текущая выставка', 1, 10);

\Expo\App\Http\Controllers\Components\ContinuousSlider::renderComponent('Лучшие фото месяца', 'best', 10);

\Expo\App\Http\Controllers\Components\SmallGrid::renderComponent('Последние опубликованные', 'latest', 12);

\Expo\App\Http\Controllers\Components\ContinuousSlider::renderComponent('Предыдущая выставка', 'latest', 10);

\Expo\App\Http\Controllers\Components\TextSlider::renderComponent('Фотографы мехмата', 'latest', 10);

\Expo\App\Http\Controllers\Components\Carousel::renderComponent('Подборка команды мехмата', 2, 5);

\Expo\App\Http\Controllers\Components\TextSlider::renderComponent('Фильтры', 'filters', 3);

\Expo\App\Models\DataBaseConnection::close();
