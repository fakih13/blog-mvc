<?php


ob_start();


if (isset($_SESSION['success_message'])) {
    $message =  $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
?>


<?php if (isset($message)) : ?>
    <div class="alert alert-success" role="alert">
        <?= $message ?>
    </div>
<?php endif ?>

<?php if (isset($errors)) : ?>
    <div class="alert alert-danger" role="alert">
        <?php foreach ($errors as $error) : ?>
            <?= $error ?><br>
        <?php endforeach ?>
    </div>
<?php endif ?>

<form action="" method="post" class="w-70 m-auto">
    <h1>login admin</h1>
    <div class="mb-3 w-50">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="" class="form-control">
    </div>
    <div class="mb-3 w-50">
        <label for="password" class="form-label">Mot de passe</label>
        <input type="password" name="password" id="password" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary">tester</button>
</form>

<?php

$content = ob_get_clean();

require_once('../src/views/admin/layout.php');
