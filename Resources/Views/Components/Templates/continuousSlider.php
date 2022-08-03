<hr>
<h2><?= $headerText ?></h2>
<div class="scrolling-wrapper d-flex mmd-slider-infinite">
    <?php foreach ($photos as $photo) : ?>
        <a href="/photo/<?= $photo['photoID'] ?>"><img src="<?= '/uploads/photos/' . $photo['location'] ?>"
                                                       alt="<?= $photo['altText'] ?>"></a>
    <?php endforeach; ?>
</div>