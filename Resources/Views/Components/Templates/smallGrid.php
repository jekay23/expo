<hr>
<h2><?= $headerText ?></h2>
<div class="container-fluid small-grid text-center mb-2">
    <?php foreach ($blocks as $block) : ?>
        <div class="small-grid-triple d-inline-flex">
            <?php foreach ($block as $photo) : ?>
                <a class="mmd-small-grid-image" href="/photo/<?= $photo['photoID'] ?>">
                    <img alt="<?= $photo['altText'] ?>" class="mmd-image"
                         src="<?= '/uploads/photos/' . $photo['location'] ?>">
                    <img class="mmd-like" src="/image/emptyWhiteHeart.png" alt="Непоставленный лайк">
                </a>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>