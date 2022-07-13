<div class="mt-4 text-center">
    <h1 class="mb-5"><strong>Загрузите ваши фотографии</strong></h1>
    <form method="POST" action="/api/upload" enctype="multipart/form-data">
        <div>
            <input class="mmd-button text-center bg-secondary" type="file" id="files" name="files[]" value="Выберите..." accept="image/jpeg, image/png" multiple/>
        </div>
        <div>
            <input class="mmd-button" type="submit" value="Загрузить"/>
        </div>
    </form>
</div>