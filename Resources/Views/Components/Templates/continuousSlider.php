<hr>
<h2><?= $headerText ?></h2>
<div class="scrolling-wrapper d-flex mmd-slider-infinite">
    <?php foreach ($photos as $photo) : ?>
        <?= $photo->render() ?>
    <?php endforeach; ?>
</div>