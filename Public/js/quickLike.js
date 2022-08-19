(function ($) {
    $('.mmd-like').click(function (e) {
        let action = '';
        if (e.target.classList.contains('mmd-liked')) {
            e.target.alt = 'Непоставленный лайк';
            e.target.classList.remove('mmd-liked');
            e.target.classList.add('mmd-not-liked');
            e.target.src = window.location.origin + '/image/emptyWhiteHeart.png';
            action = 'dislike';
        } else if (e.target.classList.contains('mmd-not-liked')) {
            e.target.alt = 'Лайк';
            e.target.classList.remove('mmd-not-liked');
            e.target.classList.add('mmd-liked');
            e.target.src = window.location.origin + '/image/filledHeart.png';
            action = 'like';
        }
        const imageLink = e.target.parentElement.children[0].src;
        const imageLinkArray = imageLink.split('/');
        const imageName = imageLinkArray[imageLinkArray.length - 1];
        $.get('/api/quick-' + action + '?name=' + imageName);
        e.preventDefault();
    });
})(jQuery);