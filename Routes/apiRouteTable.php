<?php

use Expo\App\Http\Controllers\Api\AdminActions\ChangeData;
use Expo\App\Http\Controllers\Api\AdminActions\GetData;
use Expo\App\Http\Controllers\Api\UserActions\Liking;
use Expo\App\Http\Controllers\Api\UserActions\PhotoUploading;
use Expo\App\Http\Controllers\Api\UserActions\ProfileEditing;
use Expo\App\Http\Controllers\Authentication;
use Expo\App\Mail\EmailSender;
use Expo\Routes\ApiRouter;

ApiRouter::saveCallback('sign-in', function () {
    Authentication::authenticate('sign-in');
});

ApiRouter::saveCallback('sign-in', function () {
    Authentication::authenticate('sign-in');
});

ApiRouter::saveCallback('sign-up', function () {
    Authentication::authenticate('sign-up');
});

ApiRouter::saveCallback('upload', function () {
    PhotoUploading::upload();
});

ApiRouter::saveCallback('edit-profile', function () {
    ProfileEditing::editProfile();
});

ApiRouter::saveCallback('change-avatar', function () {
    PhotoUploading::changeAvatar();
});

ApiRouter::saveCallback('change-password-email', function () {
    Authentication::changePasswordOrEmail();
});

ApiRouter::saveCallback('sign-out', function () {
    Authentication::signOut();
});

ApiRouter::saveCallback('like', function () {
    Liking::toggleLike('like');
});

ApiRouter::saveCallback('dislike', function () {
    Liking::toggleLike('dislike');
});

ApiRouter::saveCallback('users', function () {
    GetData::getUsers();
});

ApiRouter::saveCallback('photos', function () {
    GetData::getPhotos();
});

ApiRouter::saveCallback('compilations', function () {
    GetData::getCompilations();
});

ApiRouter::saveCallback('compilation-items', function () {
    GetData::getCompilationItems();
});

ApiRouter::saveCallback('changeDesc', function () {
    ChangeData::change('description');
});

ApiRouter::saveCallback('changeName', function () {
    ChangeData::change('name');
});

ApiRouter::saveCallback('makeExhibit', function () {
    ChangeData::change('isExhibit');
});

ApiRouter::saveCallback('hideCompilation', function () {
    ChangeData::change('isHidden');
});

ApiRouter::saveCallback('hideProfile', function () {
    ChangeData::change('isHiddenProfile');
});

ApiRouter::saveCallback('hideBio', function () {
    ChangeData::change('isHiddenBio');
});

ApiRouter::saveCallback('hideAvatar', function () {
    ChangeData::change('isHiddenAvatar');
});

ApiRouter::saveCallback('changeUserLevel', function () {
    ChangeData::change('updateAccessLevel');
});

ApiRouter::saveCallback('hidePhoto', function () {
    ChangeData::change('isPhotoHidden');
});

ApiRouter::saveCallback('createCompilation', function () {
    ChangeData::createCompilation();
});

ApiRouter::saveCallback('addCompilationItem', function () {
    ChangeData::addCompilationItem();
});

ApiRouter::saveCallback('removeCompilationItem', function () {
    ChangeData::removeCompilationItem();
});

ApiRouter::saveCallback('checkEmail', function () {
    EmailSender::sendTestEmail();
});

ApiRouter::saveCallback('verify', function () {
    Authentication::verifyEmail();
});

ApiRouter::saveCallback('requestRestore', function () {
    Authentication::requestRestore();
});

ApiRouter::saveCallback('restore', function () {
    Authentication::restorePassword();
});

ApiRouter::saveCallback('quick-like', function () {
    Liking::quickAction('like');
});

ApiRouter::saveCallback('quick-dislike', function () {
    Liking::quickAction('dislike');
});
