$(document).ready(function () {
    if ('ontouchstart' in document.documentElement) {
    } else {
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

$(document).ready(function () {
    if ('ontouchstart' in document.documentElement) {
    } else {
        $('.mmd-slider-infinite').slick({
            infinite: true,
            speed: 300,
            variableWidth: true,
            arrows: false,
            draggable: true,
            slidesToScroll: 3
        });
    }
});