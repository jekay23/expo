$(document).ready(function () {
    $('.mmd-slider').slick({
        infinite: false,
        speed: 300,
        variableWidth: true,
        arrows: false,
        draggable: true,
        slidesToScroll: 5
    });
});

$(document).ready(function () {
    $('.mmd-slider-infinite').slick({
        infinite: true,
        speed: 300,
        variableWidth: true,
        arrows: false,
        draggable: true,
        slidesToScroll: 5
    });
});