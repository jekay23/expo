<?php

namespace Expo\Resources\Views\Pages;

use Expo\Resources\Views\View;

class Photo
{
    public static function render(bool &$stickFooter, $photo)
    {
        $varNames = null;
        $stickFooter = true;
        switch ($photo['likeStatus']) {
            case 'notSignedIn':
                $photo['likeImage'] = '/image/emptyBlackHeart.png';
                $photo['likeAlt'] = 'Непоставленный лайк';
                $photo['likeText'] = 'Войдите, чтобы оценить';
                $uriQuery = http_build_query(['message' => 'Войдите, чтобы оценить', 'color' => 'red']);
                $photo['likeHref'] = "/sign-in?$uriQuery";
                break;
            case 'author':
                $photo['likeImage'] = '/image/filledHeart.png';
                $photo['likeAlt'] = 'Поставленный лайк';
                $photo['likeText'] = 'Это ваше фото';
                $photo['likeHref'] = '';
                break;
            case 'liked':
                $photo['likeImage'] = '/image/filledHeart.png';
                $photo['likeAlt'] = 'Поставленный лайк';
                $photo['likeText'] = 'Вам понравилось это фото';
                $uriQuery = http_build_query(['photoID' => $photo['photoID']]);
                $photo['likeHref'] = "/api/dislike?$uriQuery";
                break;
            case 'notLiked':
                $photo['likeImage'] = '/image/emptyBlackHeart.png';
                $photo['likeAlt'] = 'Непоставленный лайк';
                $photo['likeText'] = 'Вы ещё не оценили это фото';
                $uriQuery = http_build_query(['photoID' => $photo['photoID']]);
                $photo['likeHref'] = "/api/like?$uriQuery";
                break;
        }

        if (isset($photo)) {
            $varNames = ['photo'];
            View::requireTemplate('photo', 'Page', compact($varNames));
        }
    }
}
