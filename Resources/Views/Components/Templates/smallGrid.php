<hr>
<h2><?= $headerText ?></h2>
<div class="container-fluid small-grid text-center mb-2">
    <?php foreach ($blocks as $block) : ?>
        <div class="small-grid-triple d-inline-flex">
            <?php foreach ($block as $photo) : ?>
                <a href="/photo/<?= $photo['photoID'] ?>"><img src="<?= '/uploads/photos/' . $photo['location'] ?>" alt="<?= $photo['altText'] ?>"></a>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>