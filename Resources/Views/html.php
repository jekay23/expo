<!DOCTYPE html>
<html lang="ru">
    <head>
        <title><?= $title ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/x-icon" href="/image/favicon.ico">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="/css/customMMD.css" rel="stylesheet">
    </head>
    <body>
        <?php require 'Components/Templates/header.php'; ?>
        <main class="container-fluid" role="main" style="padding-top: 70px">
            <?php require $template; ?>
        </main>
        <?php require 'Components/Templates/footer.php'; ?>
        <script crossorigin="anonymous"
                integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2"
                src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
