<div class="text-center pt-5 pb-3">
    <h1><strong>Чтобы подтвердить почту, ещё раз войдите в ваш аккаунт</strong></h1>
</div>
<form class="container-fluid text-center mmd-sign-in" method="post" target="_self" action="/api/verify?token=<?= $token?>">
    <?php Expo\Resources\Views\Components\TextField::render('signIn', 'Email', ['name' => 'email', 'required' => true]) ?>
    <?php Expo\Resources\Views\Components\TextField::render('signIn', 'Пароль', ['type' => 'password', 'name' => 'password', 'required' => true]) ?>
    <button class="mmd-thin-button" type="submit">
        Подтвердить
    </button>
</form>