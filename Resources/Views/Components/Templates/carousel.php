<hr>
<h2><?= $headerText ?></h2>
<div id="photoCarousel" class="carousel carousel-dark slide py-2" data-bs-ride="false">
    <div class="carousel-inner">
        <?php foreach ($photos as $photo) : ?>
            <div class="carousel-item <?= $photo['carouselStatus'] ?>">
                <div class="d-flex justify-content-center">
                    <a class="mmd-carousel-image" href="/photo/<?= $photo['photoID'] ?>">
                        <img alt="<?= $photo['altText'] ?>" class="mmd-image"
                             src="<?= '/uploads/photos/' . $photo['location'] ?>">
                        <?php \Expo\Resources\Views\Components\Like::render(isset($photo['likeID'])) ?>
                    </a>
                </div>
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