<div class="row pt-3 justify-content-center mmd-profile">
    <div class="col-12 col-lg-5">
        <div class="row">
            <div style="width: 12rem">
                <img src="<?= $user['avatarLocation'] ?>" alt="Аватар пользвателя <?= $user['name'] ?>">
            </div>
            <div class="col d-flex flex-wrap align-content-center">
                <h1 style="font-size: xx-large; font-weight: bold; margin-bottom: 0; width: 100%"><?= $user['name'] ?></h1>
                <?php if ($user['ownProfile']['status']) :?>
                    <p style="margin-bottom: 0"><a href="<?= $user['ownProfile']['editLink'] ?>">Редактировать профиль</a></p>
                <?php endif; ?>
            </div>
        </div>
        <div>
            <p class="ms-3" style="max-width: 38rem"><?= $user['bio'] ?></p>
        </div>
    </div>
    <div class="col-12 col-lg-5">
        <div class="row justify-content-start" style="height: 3rem">
            <div class="col d-flex justify-content-end align-items-center px-1">
                <strong style="font-size: 350%; color: #939292; font-family: 'Montserrat', sans-serif; text-transform: uppercase;"><?= $user['numOfPhotos'] ?></strong>
            </div>
            <div class="col-8 d-flex justify-content-start align-items-center px-1">
                <strong style="font-size: 150%; color: #939292; font-family: 'Montserrat', sans-serif; text-transform: uppercase;">фотографий</strong>
            </div>
        </div>
        <div class="row justify-content-end" style="height: 3rem">
            <div class="col d-flex justify-content-end align-items-center px-1">
                <strong style="font-size: 350%; color: #939292; font-family: 'Montserrat', sans-serif; text-transform: uppercase;"><?= $user['numOfLikes'] ?></strong>
            </div>
            <div class="col-5 d-flex justify-content-start align-items-center px-1">
                <strong style="font-size: 150%; color: #939292; font-family: 'Montserrat', sans-serif; text-transform: uppercase;">лайков</strong>
            </div>
        </div>
        <div class="text-center">
            <button class="mmd-button mt-5" type="button" <?= $button['href'] ?>><p class="m-0"><?= $button['name'] ?></p></button>
        </div>
    </div>
</div>