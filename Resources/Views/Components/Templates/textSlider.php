<hr>
<h2><?= $headerText ?></h2>
<div class="scrolling-wrapper">
    <?php foreach ($textFields as $textField) {?>
        <a href="<?= $textField['href'] ?>">
            <div class="card">
                <p><?= $textField['name'] ?></p>
            </div>
        </a>
    <?php } ?>
</div>