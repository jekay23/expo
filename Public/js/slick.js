(function ($) {
    $(document).ready(function () {
        if (!('ontouchstart' in document.documentElement)) {
            $('.mmd-slider-infinite').slick({
                infinite: true,
                speed: 300,
                variableWidth: true,
                arrows: false,
                draggable: true,
                slidesToScroll: 3
            });
            $('.mmd-slider').slick({
                infinite: false,
                speed: 300,
                variableWidth: true,
                arrows: false,
                draggable: true,
                slidesToScroll: 3
            });
        }
    });
})(jQuery);