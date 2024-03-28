<?php

ob_start();
?>
<?php if (isset($promotions)) : ?>
  <?php foreach ($promotions as $key) : ?>
    <a href="/<?= $key['id'] ?>"><?= $key['name'] ?></a>
    <span><?= $key['statut'] ?></span>
    <span><?= $key['type'] ?></span>
    <span><?= $key['used'] ?></span>
  <?php endforeach ?>

<?php else : ?>

  <div>
    <p>Aucune Promotion trouvée</p>
  </div>

<?php endif ?>
<?php

$content = ob_get_clean();

require_once('../src/views/admin/layout.php');
