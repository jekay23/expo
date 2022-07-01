<div class="text-center pt-5 pb-3">
    <h1><strong>Войдите в ваш аккаунт</strong></h1>
</div>
<div class="container-fluid text-center mmd-sign-in">
    <?php Expo\Resources\Views\Components\TextField::render('Email') ?>
    <?php Expo\Resources\Views\Components\TextField::render('Пароль', ['type' => 'password']) ?>
    <button type="submit">
        Войти
    </button>
    <p><a href="/">Восстановить пароль</a></p>
    <p><a href="/sign-up">Зарегестрироваться</a></p>
</div>
