<div class="text-center pt-5 pb-3">
    <h1><strong>Восстановление пароля</strong></h1>
</div>
<form class="container-fluid text-center mmd-sign-in" method="post" target="_self" action="/api/requestRestore">
    <?php Expo\Resources\Views\Components\TextField::render('signIn', 'Email', ['name' => 'email', 'required' => true]) ?>
    <button class="mmd-thin-button" type="submit">
        Восстановить
    </button>
</form>