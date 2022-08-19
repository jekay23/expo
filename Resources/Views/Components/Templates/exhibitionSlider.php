<h2><?= $headerText ?></h2>
<div class="scrolling-wrapper d-flex mmd-slider">
    <div class="stack ps-md-2">
        <h3 class="exhibit-title">
            <a href="/compilation/<?= $exhibition['compilationID']?>" style="text-decoration: none">
                Тема: <strong><?= $exhibition['name'] ?></strong>
            </a>
        </h3>
        <p class="exhibit-descr"><?= $exhibition['description'] ?></div>
    <div class="stack mmd-exhibition">
        <?php foreach ($rows as $row) : ?>
            <div class="one-of-stack d-flex justify-content-evenly">
                <?php foreach ($row as $photo) : ?>
                    <?= $photo->render() ?>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>