<a class="<?= $photo['class'] ?>" href="/photo/<?= $photo['photoID'] ?>">
    <img alt="<?= $photo['altText'] ?>" class="mmd-image" src="<?= '/uploads/photos/' . $photo['location'] ?>">
    <?= $like->render() ?>
</a>
