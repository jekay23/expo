<h1 class="mt-4 text-center text-primary"><strong><?= $compilation['name'] ?></strong></h1>
<?php if ('' != $compilation['description']): ?>
    <div class="row justify-content-center">
        <p class="text-center col-12 col-lg-8" style="max-width: 60rem"><?= $compilation['description'] ?></p>
    </div>
<?php endif; ?>