<div class="text-center pt-5 pb-3">
    <h1><strong>Войдите в ваш аккаунт</strong></h1>
</div>
<form class="container-fluid text-center mmd-sign-in" method="post" target="_self" action="/api/sign-in">
    <?php Expo\Resources\Views\Components\TextField::render('signIn', 'Email', ['name' => 'email', 'required' => true]) ?>
    <?php Expo\Resources\Views\Components\TextField::render('signIn', 'Пароль', ['type' => 'password', 'name' => 'password', 'required' => true]) ?>
    <button class="mmd-thin-button" type="submit">
        Войти
    </button>
    <p><a href="/request-restore">Восстановить пароль</a></p>
    <p><a href="/sign-up">Зарегистрироваться</a></p>
</form>
