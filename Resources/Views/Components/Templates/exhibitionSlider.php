<h2><?= $headerText ?></h2>
<div class="scrolling-wrapper d-flex">
    <div class="stack ps-md-2">
        <h3 class="exhibit-title">Тема: <strong><?= $exhibitionName ?></strong></h3>
        <p class="exhibit-descr"><?= $exhibitionDescription ?></div>
    <div class="stack mmd-exhibition">
        <?php foreach ($rows as $row) : ?>
            <div class="one-of-stack d-flex justify-content-evenly">
                <?php foreach ($row as $photo) : ?>
                    <img src= <?= '/uploads/photos/' . $photo['location'] ?> alt="<?= $photo['altText'] ?>">
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>