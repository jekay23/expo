<hr>
<h2><?= $headerText ?></h2>
<div id="photoCarousel" class="carousel carousel-dark slide py-2" data-bs-ride="false">
    <div class="carousel-inner">
        <?php foreach ($photos as $photo) : ?>
            <div class="carousel-item <?= $photo['carouselStatus'] ?> text-center">
                <img src=<?= '/uploads/photos/' . $photo['location'] ?> alt="<?= $photo['altText'] ?>">
            </div>
        <?php endforeach; ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#photoCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#photoCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>