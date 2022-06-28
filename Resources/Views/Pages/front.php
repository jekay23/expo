<?php

\Expo\App\Http\Controllers\Components\ExhibitionSlider::renderComponent('Текущая выставка', 1, 10);

\Expo\App\Http\Controllers\Components\ContinuousSlider::renderComponent('Лучшие фото месяца', 'best', 10);

\Expo\App\Http\Controllers\Components\SmallGrid::renderComponent('Последние опубликованные', 'latest', 12);

\Expo\App\Http\Controllers\Components\ContinuousSlider::renderComponent('Предыдущая выставка', 'latest', 10);

\Expo\Resources\Views\View::renderHeader2('Фотографы мехмата');
$sliderText = 'photographers';
\Expo\App\Http\Controllers\Components\TextSlider::renderComponent($sliderText);

\Expo\Resources\Views\View::renderHeader2('Подборка команды мехмата');
require __DIR__ . '/../Components/carousel.php';

\Expo\Resources\Views\View::renderHeader2('Фильтры');
$sliderText = 'filters';
\Expo\App\Http\Controllers\Components\TextSlider::renderComponent($sliderText);
