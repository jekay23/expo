<hr>
<h2><?= $headerText ?></h2>
<div class="scrolling-wrapper d-flex">
    <?php foreach ($photos as $photo) : ?>
        <img src=<?= '/uploads/photos/' . $photo['location'] ?> alt="<?= $photo['altText'] ?>">
    <?php endforeach; ?>
</div>