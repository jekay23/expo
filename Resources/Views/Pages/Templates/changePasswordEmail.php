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
            <h3 class="mb-3">Изменить email</h3>
            <?php TextField::render('changePasswordEmail', 'Email', ['name' => 'email', 'value' => $email]); ?>
            <hr>
            <h3 class="mt-4 mb-3">Изменить пароль</h3>
            <?php TextField::render('changePasswordEmail', 'Новый пароль', ['name' => 'newPassword', 'type' => 'password']); ?>
            <?php TextField::render('changePasswordEmail', 'Новый пароль ещё раз', ['name' => 'newPasswordAgain', 'type' => 'password']); ?>
            <hr>
            <h3 class="mt-4 mb-3">Подтвердить действие и сохранить</h3>
            <?php TextField::render('changePasswordEmail', 'Старый пароль', ['name' => 'oldPassword', 'type' => 'password', 'required' => true]); ?>
            <div class="row justify-content-center mb-5">
                <div class="col-6 mmd-edit-wrap d-flex justify-content-center justify-content-sm-start p-0 px-sm-3 mb-5">
                    <button class="mmd-thin-button mt-0 mb-5" type="submit">Сохранить</button>
                </div>
            </div>
        </form>
    </div>
</div>