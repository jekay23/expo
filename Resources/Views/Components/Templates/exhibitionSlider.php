<h2><?= $headerText ?></h2>
<div class="scrolling-wrapper d-flex mmd-slider">
    <div class="stack ps-md-2">
        <h3 class="exhibit-title">Тема: <strong><?= $exhibitionName ?></strong></h3>
        <p class="exhibit-descr"><?= $exhibitionDesc ?></div>
    <div class="stack mmd-exhibition">
        <?php foreach ($rows as $row) : ?>
            <div class="one-of-stack d-flex justify-content-evenly">
                <?php foreach ($row as $photo) : ?>
                    <a class="mmd-exhibit-slider-photo" href="/photo/<?= $photo['photoID'] ?>">
                        <img alt="<?= $photo['altText'] ?>" class="mmd-image"
                             src="<?= '/uploads/photos/' . $photo['location'] ?>">
                        <?php \Expo\Resources\Views\Components\Like::render($photo['liked']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>