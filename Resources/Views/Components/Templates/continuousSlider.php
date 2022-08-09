<hr>
<h2><?= $headerText ?></h2>
<div class="scrolling-wrapper d-flex mmd-slider-infinite">
    <?php foreach ($photos as $photo) : ?>
        <a class="mmd-slider-image" href="/photo/<?= $photo['photoID'] ?>">
            <img src="<?= '/uploads/photos/' . $photo['location'] ?>" alt="<?= $photo['altText'] ?>" class="mmd-image">
            <?php \Expo\Resources\Views\Components\Like::render($photo['liked']) ?>
        </a>
    <?php endforeach; ?>
</div>