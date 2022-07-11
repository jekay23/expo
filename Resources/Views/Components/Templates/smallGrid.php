<hr>
<h2><?= $headerText ?></h2>
<div class="container-fluid small-grid text-center mb-2">
    <?php foreach ($triples as $triple) : ?>
        <div class="small-grid-triple d-inline-flex">
            <?php foreach ($triple as $photo) : ?>
                <img src="<?= '/uploads/photos/' . $photo['location'] ?>" alt="<?= $photo['altText'] ?>">
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>