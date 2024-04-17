<?php

ob_start();
?>

<?php if (isset($data)) : ?>
  <?php foreach ($data['data'] as $key => $value) : ?>
    <?php foreach ($value as $v) : ?>
      <?= $v ?>

    <?php endforeach ?>
    <br>
  <?php endforeach ?>

<?php endif ?>

<?php

$content = ob_get_clean();

require_once('../src/views/admin/layout.php');
