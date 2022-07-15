<div class="mt-4 text-center">
    <h1 class="mb-5"><strong>Загрузите ваш новый аватар</strong></h1>
    <form method="POST" action="/api/change-avatar" enctype="multipart/form-data">
        <div>
            <input class="mmd-button text-center bg-info" type="file" id="file" name="file" value="Выберите..." accept="image/jpeg, image/png"/>
        </div>
        <div>
            <input class="mmd-button" type="submit" value="Загрузить"/>
        </div>
    </form>
</div>