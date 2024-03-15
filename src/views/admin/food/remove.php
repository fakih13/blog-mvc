<?php

ob_start();
?>

<h1>Plats Pti' Riad</h1>

<p>supprimer</p>
<?php foreach ($results['data'] as $result) : ?>
  <div class="d-flex">
    <input type="hidden" name="idRecipe" value="<?= $result['PlatID'] ?>">
    <p class="mx-2 nomRecipe"><?= $result['Nom'] ?></p>
    <p class="mx-2"><?= $result['Prix'] ?></p>
    <button class="removeRecipe" data-bs-toggle="modal" data-bs-target="#deleteModal">X</button>
  </div>
<?php endforeach; ?>

<div id="deleteModal" class="modal fade" tabindex="-1" aria-labelledby="deleteModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">supprimer le plat</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="modalBody">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-danger" id="deleteButtonModale">Supprimer</button>
      </div>
    </div>
  </div>
</div>

<script src="../../src/views/admin/js/remove.js"></script>
<?php

$content = ob_get_clean();

require_once('../src/views/admin/layout.php');
