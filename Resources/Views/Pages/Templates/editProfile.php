<div class="row pt-3 justify-content-center mmd-edit-profile">
    <div class="col-12 col-lg-3 text-center">
        <div>
            <img src="<?= $avatarLocation ?>" alt="Аватар пользвателя <?= $profileName ?>">
        </div>
        <p><a href="/profile/<?= $userID ?>/change-avatar">Обновить фото профиля</a></p>
        <div>
            <p class="mmd-small-note">обратите внимание, что на данный момент сайт не поддерживает обрезку фотографий</p>
        </div>
        <div class="justify-content-center">
            <button class="mmd-thin-button mt-0 bg-info" type="button" onclick="location.href='/api/sign-out'">Выйти</button>
        </div>
    </div>
    <form class="mt-3 col-12 col-lg-7" method="post" target="_self" action="/api/edit-profile">
        <div class="row justify-content-center justify-content-sm-start">
            <div class="col-12 col-sm-2 text-center text-sm-end mt-2 px-0 align-self-start"><p>Имя</p></div>
            <div class="col-10 col-sm-8 mmd-edit-wrap p-0 px-sm-3"><input name="name" value="<?= $profileName ?>"></div>
        </div>
        <div class="row justify-content-center justify-content-sm-start">
            <div class="col-12 col-sm-2 text-center text-sm-end mt-2 px-0 align-self-start"><p>Email</p></div>
            <div class="col-10 col-sm-8 mmd-edit-wrap p-0 px-sm-3"><input class="text-info" name="email" value="<?= $email ?>" disabled></div>
        </div>
        <div class="row justify-content-center justify-content-sm-start">
            <div class="col-12 col-sm-2 text-center text-sm-end mt-2 px-0 align-self-start"><p>Обращение</p></div>
            <div class="col-3 p-0 ps-sm-3 mmd-edit-wrap">
                <select class="form-select mmd-select" name="pronoun" id="pronounChoice">
                    <option value="none" <?= $pronounSelector['none'] ?>>—</option>
                    <option value="he" <?= $pronounSelector['he'] ?>>он</option>
                    <option value="she" <?= $pronounSelector['she'] ?>>она</option>
                </select>
            </div>
            <div class="col-12 col-sm-5 text-center text-sm-end pt-4 pb-3 py-sm-0 pe-sm-3">
                <a href="/profile/<?= $userID ?>/change-password-email">Изменить пароль и email</a>
            </div>
        </div>
        <div class="row justify-content-center justify-content-sm-start">
            <div class="col-12 col-sm-2 text-center text-sm-end mt-2 px-0 align-self-start">
                <p>Описание</p>
                <p class="mmd-small-note">рекомендуем уложиться в 4 строчки</p>
            </div>
            <div class="col-10 col-sm-8 mmd-edit-wrap p-0 px-sm-3">
                <textarea name="bio" id="bio"><?= $bio ?></textarea>
            </div>
        </div>
        <div class="row justify-content-center justify-content-sm-start">
            <div class="col-12 col-sm-2 text-center text-sm-end mt-2 px-0 align-self-start">
                <p>Контакты</p>
                <p class="mmd-small-note">на случай, если с вами захотят договориться о фотосессии</p>
            </div>
            <div class="col-10 col-sm-8 mmd-edit-wrap p-0 px-sm-3">
                <textarea name="contact" id="contact"><?= $contact ?></textarea>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-8 mmd-edit-wrap d-flex justify-content-center justify-content-sm-start p-0 px-sm-3">
                <button class="mmd-thin-button mt-0" type="submit">Сохранить</button>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-8 mmd-edit-wrap d-flex text-center text-sm-start px-0 px-sm-3 py-0 py-lg-5">
                Загрузить фотографии вы можете через ваш профиль
            </div>
        </div>
    </form>
</div>