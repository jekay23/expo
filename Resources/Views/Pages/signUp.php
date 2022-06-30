<?php $stickFooter = true; ?>
<div class="text-center pt-5 pb-3">
    <h1><strong>Зарегестрируйтесь</strong></h1>
</div>
<div class="container-fluid text-center mmd-sign-in">
    <div class="row">
        <p class="d-inline-flex">Email</p>
        <div class="d-inline-flex text-start mmd-input-wrap"><input></div>
        <p class="d-inline-flex"> </p>
    </div>
    <div class="row">
        <p class="d-inline-flex">Имя</p>
        <div class="d-inline-flex text-start mmd-input-wrap"><input></div>
        <p class="d-inline-flex"> </p>
    </div>
    <div class="row">
        <p class="d-inline-flex">Пароль</p>
        <div class="d-inline-flex text-start mmd-input-wrap"><input type="password"></div>
        <p class="d-inline-flex"> </p>
    </div>
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