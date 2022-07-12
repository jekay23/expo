<div class="mt-4 text-center">
    <h1 class="mb-5"><strong>Загрузите ваши фотографии</strong></h1>
    <form method="POST" action="/api/upload" enctype="multipart/form-data">
        <input type="file" id="files" name="files[]" value="Выберите..." accept="image/jpeg, image/png" multiple/>
        <input type="submit" value="Загрузить"/>
    </form>
</div>