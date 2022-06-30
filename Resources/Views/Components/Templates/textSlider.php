<h2><?= $headerText ?></h2>
<div class="scrolling-wrapper">
    <?php foreach ($textFields as $textField) {?>
        <div class="card">
            <p><?= $textField['name'] ?></p>
        </div>
    <?php } ?>
</div>