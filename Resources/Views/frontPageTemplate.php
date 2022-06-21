<main class="container-fluid" role="main" style="padding-top: 70px">
    <?php
    \Expo\Resources\Views\View::renderHeader2('Текущая выставка');
    require 'Components/exhibitionSlider.php';

    \Expo\Resources\Views\View::renderHeader2('Лучшие фото месяца');
    require 'Components/continuousSlider.php';

    \Expo\Resources\Views\View::renderHeader2('Последние опубликованные');
    require 'Components/smallGrid.php';

    \Expo\Resources\Views\View::renderHeader2('Предыдущая выставка');
    require 'Components/continuousSlider.php';

    \Expo\Resources\Views\View::renderHeader2('Фотографы мехмата');
    $sliderText = 'photographers';
    \Expo\App\Http\Controllers\Components\textSlider::renderSlider($sliderText);

    \Expo\Resources\Views\View::renderHeader2('Подборка команды мехмата');
    require 'Components/carousel.php';

    \Expo\Resources\Views\View::renderHeader2('Фильтры');
    $sliderText = 'filters';
    \Expo\App\Http\Controllers\Components\textSlider::renderSlider($sliderText);
    ?>

</main>