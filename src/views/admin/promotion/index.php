<?php

ob_start();
?>
<?php if (isset($data)) : ?>
  <?php foreach ($data['promotion'] as $key) : ?>
    <a href="promotion/view/<?= $key['id'] ?>"><?= $key['name'] ?>
      <span><?= $key['name'] ?></span>
      <span><?= $key['status'] ?></span>
      <span><?= $key['method'] ?></span>
      <span><?= $key['target_type'] ?></span>
      <span><?= $key['target_id'] ?></span>
      <span><?= $key['percentage'] ?></span>
      <span><?= $key['start_date'] ?></span>
      <span><?= $key['end_date'] ?></span>
    </a>
  <?php endforeach ?>

<?php else : ?>

  <div>
    <p>Aucune Promotion trouvée</p>
  </div>

<?php endif ?>
<?php

$content = ob_get_clean();

require_once('../src/views/admin/layout.php');
