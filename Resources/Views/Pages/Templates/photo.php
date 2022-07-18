<div class="row justify-content-center mmd-photo-page">
    <div class="col-12 col-lg-7 d-flex justify-content-center mt-3">
        <img src="/uploads/photos/<?= $photo['location'] ?>" alt="<?= $photo['altText'] ?>">
    </div>
    <div class="col-12 col-lg-3 mt-4">
        <div class="row" onclick="location.href='/profile/<?= $photo['authorID'] ?>">
            <div class="col-6 col-lg-4 d-flex justify-content-end">
                <a href="/profile/<?= $photo['authorID'] ?>"><img src="<?= $photo['authorAvatarLocation'] ?>" alt="Аватар пользоветаля <?= $photo['authorName'] ?>"></a>
            </div>
            <div class="col-6 col-lg-8 d-flex justify-content-start align-items-center">
                <a href="/profile/<?= $photo['authorID'] ?>"><p><?= $photo['authorName'] ?></p></a>
            </div>
        </div>
        <div class="row">
        </div>
    </div>
</div>