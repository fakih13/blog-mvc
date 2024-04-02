<?php

ob_start();
?>


<?php if (isset($data)) : ?>
  <?php var_dump($data) ?>
  <?php foreach ($data['promotion'] as $key => $value) : ?>
    <?php foreach ($value as $k => $valueData) : ?>
      <?php if ($k === 'id') : ?>
        <?php continue ?>
      <?php endif ?>
      <?php if ($k === 'end_date') : ?>
        <?php if ($valueData === null) : ?>
          <?php $valueData = '0000-00-00'; ?>
        <?php endif ?>
        <div class="mb-3">
          <label for="$k" class="form-label"><?= $k ?></label>
          <input type="date" id="$k" class="form-control" value="<?= $valueData ?>">
        <?php else : ?>
          <div class="mb-3">
            <label for="$k" class="form-label"><?= $k ?></label>
            <input type="text" id="$k" class="form-control" value="<?= $valueData ?>" disabled>
          </div>
        <?php endif ?>
      <?php endforeach ?>
    <?php endforeach ?>

  <?php else : ?>

    <div>
      <p>Aucune Promotion trouvée</p>
    </div>

  <?php endif ?>
  <?php

  $content = ob_get_clean();

  require_once('../src/views/admin/layout.php');
