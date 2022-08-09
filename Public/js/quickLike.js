$('.mmd-like').click(function (e) {
    let action = '';
    switch (e.target.alt) {
        case 'Лайк':
            e.target.alt = 'Непоставленный лайк';
            e.target.src = window.location.origin + '/image/emptyWhiteHeart.png';
            action = 'dislike';
            break;
        case 'Непоставленный лайк':
            e.target.alt = 'Лайк';
            e.target.src = window.location.origin + '/image/filledHeart.png';
            action = 'like';
            break;
    }
    const imageLink = e.target.parentElement.children[0].src;
    const imageLinkArray = imageLink.split('/');
    const imageName = imageLinkArray[imageLinkArray.length - 1];
    $.get('/api/quick-' + action + '?name=' + imageName);
    e.preventDefault();
});