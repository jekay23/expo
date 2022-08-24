<!DOCTYPE html>
<html lang="ru">
    <head>
        <title><?= $title ?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/x-icon" href="/image/favicon.ico">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="/css/customMMD.css" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@800&display=swap" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css">
    </head>
    <body>
        <?php \Expo\Resources\Views\Components\Header::render($userID, $requestView); ?>
        <main class="container-fluid mmd-main" role="main">
            <?php \Expo\App\Http\Controllers\Components\Announcement::render(); ?>
            <div style="padding: 0 1vw">
                <?php $renderMainCallback() ?>
            </div>
        </main>
        <?php \Expo\Resources\Views\Components\Footer::render($stickFooter); ?>
        <script crossorigin="anonymous"
                integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
                src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script type="text/javascript"
                src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
        <script src="/js/popover.js"></script>
        <script src="/js/slick.js"></script>
        <script src="/js/quickLike.js"></script>
    </body>
</html>
