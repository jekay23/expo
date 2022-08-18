<?php

use Expo\App\Http\Controllers\Api\AdminActions;
use Expo\App\Http\Controllers\Api\UserActions;
use Expo\App\Http\Controllers\Authentication;
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
    UserActions::upload();
});

ApiRouter::saveCallback('edit-profile', function () {
    UserActions::editProfile();
});

ApiRouter::saveCallback('change-avatar', function () {
    UserActions::changeAvatar();
});

ApiRouter::saveCallback('change-password-email', function () {
    Authentication::changePasswordOrEmail();
});

ApiRouter::saveCallback('sign-out', function () {
    Authentication::signOut();
});

ApiRouter::saveCallback('like', function () {
    UserActions::toggleLike('like');
});

ApiRouter::saveCallback('dislike', function () {
    UserActions::toggleLike('dislike');
});

ApiRouter::saveCallback('users', function () {
    AdminActions::getUsers();
});

ApiRouter::saveCallback('photos', function () {
    AdminActions::getPhotos();
});

ApiRouter::saveCallback('compilations', function () {
    AdminActions::getCompilations();
});

ApiRouter::saveCallback('compilation-items', function () {
    AdminActions::getCompilationItems();
});

ApiRouter::saveCallback('changeDesc', function () {
    AdminActions::change('description');
});

ApiRouter::saveCallback('changeName', function () {
    AdminActions::change('name');
});

ApiRouter::saveCallback('makeExhibit', function () {
    AdminActions::change('isExhibit');
});

ApiRouter::saveCallback('hideCompilation', function () {
    AdminActions::change('isHidden');
});

ApiRouter::saveCallback('hideProfile', function () {
    AdminActions::change('isHiddenProfile');
});

ApiRouter::saveCallback('hideBio', function () {
    AdminActions::change('isHiddenBio');
});

ApiRouter::saveCallback('hideAvatar', function () {
    AdminActions::change('isHiddenAvatar');
});

ApiRouter::saveCallback('changeUserLevel', function () {
    AdminActions::change('updateAccessLevel');
});

ApiRouter::saveCallback('hidePhoto', function () {
    AdminActions::change('isPhotoHidden');
});

ApiRouter::saveCallback('createCompilation', function () {
    AdminActions::createCompilation();
});

ApiRouter::saveCallback('addCompilationItem', function () {
    AdminActions::addCompilationItem();
});

ApiRouter::saveCallback('removeCompilationItem', function () {
    AdminActions::removeCompilationItem();
});

ApiRouter::saveCallback('checkEmail', function () {
    AdminActions::sendEmail();
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
    UserActions::quickAction('like');
});

ApiRouter::saveCallback('quick-dislike', function () {
    UserActions::quickAction('dislike');
});
