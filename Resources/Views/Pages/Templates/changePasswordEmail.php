<div class="row pt-3 justify-content-center mmd-edit-profile">
    <div class="col-12 col-lg-3 text-center">
        <div>
            <img src="<?= $avatarLocation ?>" alt="Аватар пользвателя <?= $profileName ?>">
        </div>
    </div>
    <div class="mt-3 col-12 col-lg-7 mmd-edit-profile">
        <form method="post" target="_self" action="/api/change-password-email">
            <div class="row justify-content-center justify-content-sm-start">
                <div class="col-12 col-sm-3 text-center text-sm-end mt-2 px-0 align-self-start"><p>Email</p></div>
                <div class="col-10 col-sm-8 mmd-edit-wrap p-0 px-sm-3"><input name="email" value="<?= $email ?>"></div>
            </div>
            <div class="row justify-content-center justify-content-sm-start">
                <div class="col-12 col-sm-3 text-center text-sm-end mt-2 px-0 align-self-start"><p>Старый пароль</p></div>
                <div class="col-10 col-sm-8 mmd-edit-wrap p-0 px-sm-3"><input name="oldPassword" type="password"></div>
            </div>
            <div class="row justify-content-center justify-content-sm-start">
                <div class="col-12 col-sm-3 text-center text-sm-end mt-2 px-0 align-self-start"><p>Новый пароль</p></div>
                <div class="col-10 col-sm-8 mmd-edit-wrap p-0 px-sm-3"><input name="newPassword" type="password"></div>
            </div>
            <div class="row justify-content-center justify-content-sm-start">
                <div class="col-12 col-sm-3 text-center text-sm-end mt-2 px-0 align-self-start"><p>Новый пароль ещё раз</p></div>
                <div class="col-10 col-sm-8 mmd-edit-wrap p-0 px-sm-3"><input name="newPasswordAgain" type="password"></div>
            </div>
            <div class="row justify-content-center">
                <div class="col-6 mmd-edit-wrap d-flex justify-content-center justify-content-sm-start p-0 px-sm-3">
                    <button class="mmd-thin-button mt-0" type="submit">Сохранить</button>
                </div>
            </div>
        </form>
    </div>
</div>