<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration</title>
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <?php if (isset($css)) : ?>
        <?php foreach ($css as $thecss) : ?>
            <link rel="stylesheet" href="<?= $thecss ?>">
        <?php endforeach ?>
    <?php endif ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous" defer></script>
</head>

<body>
    <header>
        <?php if (isset($_SESSION['ADMIN_EMAIL'])) : ?>
            <form action="/disconnect" method="post">
                <button type="submit">déconnexion</button>
            </form>

        <?php endif ?>
    </header>
    <main>
        <div class="container mt-5">
            <?= $content ?>
        </div>
    </main>
</body>

</html>