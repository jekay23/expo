<main class="container-fluid" role="main" style="padding-top: 70px">
    <?php
    \Expo\Resources\Views\View::renderHeader2('Текущая выставка');
    require 'Components/exhibitionSlider.php';

    \Expo\Resources\Views\View::renderHeader2('Лучшие фото месяца');
    require 'Components/continuousSlider.php'
    ?>

</main>