<?php

use Expo\Resources\Views\Components\TextField;

?>
<div class="row pt-3 justify-content-center mmd-edit-profile">
    <div class="col-12 col-lg-3 text-center">
        <div>
            <img src="<?= $avatarLocation ?>" alt="Аватар пользвателя <?= $profileName ?>">
        </div>
    </div>
    <div class="mt-3 col-12 col-lg-7 mmd-edit-profile">
        <form method="post" target="_self" action="/api/change-password-email">
            <?php TextField::render('changePasswordEmail', 'Email', ['name' => 'email', 'value' => $email]); ?>
            <?php TextField::render('changePasswordEmail', 'Старый пароль', ['name' => 'oldPassword', 'type' => 'password']); ?>
            <?php TextField::render('changePasswordEmail', 'Новый пароль', ['name' => 'newPassword', 'type' => 'password']); ?>
            <?php TextField::render('changePasswordEmail', 'Новый пароль ещё раз', ['name' => 'newPasswordAgain', 'type' => 'password']); ?>
            <div class="row justify-content-center">
                <div class="col-6 mmd-edit-wrap d-flex justify-content-center justify-content-sm-start p-0 px-sm-3">
                    <button class="mmd-thin-button mt-0" type="submit">Сохранить</button>
                </div>
            </div>
        </form>
    </div>
</div>