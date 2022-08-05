<hr>
<h2><?= $headerText ?></h2>
<div class="scrolling-wrapper d-flex mmd-slider-infinite">
    <?php foreach ($photos as $photo) : ?>
        <a class="mmd-slider-image" href="/photo/<?= $photo['photoID'] ?>">
            <img src="<?= '/uploads/photos/' . $photo['location'] ?>" alt="<?= $photo['altText'] ?>" class="mmd-image">
            <img class="mmd-like" src="/image/emptyWhiteHeart.png" alt="Непоставленный лайк">
        </a>
    <?php endforeach; ?>
</div>