<?php

namespace Expo\App\Http\Controllers\Components;

class textSlider
{
    private static $sliderTextTypes = array(
        'photographers' => array('Платон Антониу', 'Юлий Цезарь', 'Денис Коцюба', 'Слава Муравлёв',
                                 'Платон Антониу', 'Юлий Цезарь', 'Денис Коцюба', 'Слава Муравлёв',
                                 'Платон Антониу', 'Юлий Цезарь', 'Денис Коцюба', 'Слава Муравлёв'),
        'filters' => array('По дате публикации', 'По поулярности', 'По выставкам')
    );

    public static function renderSlider($sliderText) {
        $renderText = array();
        if (isset(self::$sliderTextTypes[$sliderText])) {
            $renderText = self::$sliderTextTypes[$sliderText];
        }
        require __DIR__ . '/../../../../Resources/Views/Components/textSlider.php';
}
}