<div class="text-center pt-5 pb-3">
    <h1><strong>Введите новый пароль</strong></h1>
</div>
<form class="container-fluid text-center mmd-sign-in" method="post" target="_self" action="/api/restore?token=<?= $token?>">
    <?php Expo\Resources\Views\Components\TextField::render('signIn', 'Email', ['name' => 'email', 'required' => true]) ?>
    <?php Expo\Resources\Views\Components\TextField::render('signIn', 'Новый пароль', ['type' => 'password', 'name' => 'password', 'required' => true]) ?>
    <?php Expo\Resources\Views\Components\TextField::render('signIn', 'Новый пароль', ['type' => 'password', 'name' => 'passwordAgain', 'required' => true]) ?>
    <button class="mmd-thin-button" type="submit">
        Применить
    </button>
</form>