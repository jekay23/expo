<div class="text-center pt-5 pb-3">
    <h1><strong>Войдите в ваш аккаунт</strong></h1>
</div>
<form class="container-fluid text-center mmd-sign-in" method="post" target="_self" action="/api/sign-in">
    <?php Expo\Resources\Views\Components\TextField::render('Email', ['name' => 'email']) ?>
    <?php Expo\Resources\Views\Components\TextField::render('Пароль', ['type' => 'password', 'name' => 'password']) ?>
    <button type="submit">
        Войти
    </button>
    <p><a href="/">Восстановить пароль</a></p>
    <p><a href="/sign-up">Зарегистрироваться</a></p>
</form>
