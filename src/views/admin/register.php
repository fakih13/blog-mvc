<?php
ob_start();
?>



<?php if (isset($errors)) : ?>
    <div class="alert alert-danger" role="alert">
        <?php foreach ($errors as $error) : ?>
            <?= $error ?><br>
        <?php endforeach ?>
    </div>
<?php endif ?>


<form method="post" class="w-50 m-auto">
    <h1>Inscription Admin</h1>
    <div class="input-group mb-3">
        <input type="text" class="form-control" placeholder="Nom" aria-label="firstName" name="firstName" required>
        <input type="text" class="form-control" placeholder="Prénom" aria-label="lastName" name="lastName" required>
    </div>
    <div class="input-group mb-3">
        <span class="input-group-text">Téléphone</span>
        <input type="tel" id="phone" name="phone" pattern="[0-9]{2} [0-9]{2} [0-9]{2} [0-9]{2} [0-9]{2}" required class="form-control" placeholder="06 00 00 00 00" />
        <span class="input-group-text">@</span>
        <input type="email" class="form-control" placeholder="email" aria-label="email" name="email" required>
    </div>
    <div class="input-group mb-3">
        <input type="password" class="form-control" placeholder="mot de passe" aria-label="password" name="password" required>
        <input type="password" class="form-control" placeholder="confirmation mot de passe" aria-label="password2" name="password2" required>
    </div>
    <button type="submit" class="btn btn-primary">S'inscrire</button>
</form>


<?php

$content = ob_get_clean();

require_once('../src/views/admin/layout.php');
