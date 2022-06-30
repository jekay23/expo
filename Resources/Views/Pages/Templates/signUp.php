<div class="text-center pt-5 pb-3">
    <h1><strong>Зарегестрируйтесь</strong></h1>
</div>
<div class="container-fluid text-center mmd-sign-in">
    <?php Expo\Resources\Views\Components\TextField::render('Email') ?>
    <?php Expo\Resources\Views\Components\TextField::render('Имя') ?>
    <?php Expo\Resources\Views\Components\TextField::render('Пароль', ['type' => 'password']) ?>
    <div class="row">
        <p class="d-inline-flex">Обращение</p>
        <div class="d-inline-flex text-start mmd-input-wrap">
            <select class="form-select mmd-select" name="pronoun" id="pronounChoice">
                <option value="none" selected>—</option>
                <option value="he">он</option>
                <option value="she">она</option>
            </select>
        </div>
        <p class="d-inline-flex"> </p>
    </div>
    <button type="submit">
        Зарегестрироваться
    </button>
    <p><a href="/sign-in">У меня есть профиль</a></p>
</div>