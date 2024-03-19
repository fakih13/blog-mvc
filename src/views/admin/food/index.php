<?php

ob_start();
?>

<h1>Plats Pti' Riad</h1>

<?php if (isset($results)) : ?>
  <?php if ($results['code'] === 200) : ?>
    <?php foreach ($results['recipe'] as $result) : ?>
      <div class="d-flex my-2">
        <input type="hidden" name="idRecipe" value="<?= $result['PlatID'] ?>">
        <p class="mx-2 nomRecipe"><?= $result['Nom'] ?></p>
        <p class="mx-2"><?= $result['Prix'] ?></p>
        <button class="btn btn-info mx-2 my-1 updateRecipe" data-id="<?= $result['PlatID'] ?>">Modifier</button>
        <button class=" removeRecipe btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">Supprimer</button>
      </div>
    <?php endforeach; ?>

  <?php elseif ($results['code'] === 204) : ?>

    <p>Aucun plat dans la carte <a href="food/ajouter">Ajouter</a></p>

  <?php else : ?>
    <p>Erreur veuillez contacter le webmaster</p>
  <?php endif ?>
<?php endif ?>

<div id="deleteModal" class="modal fade" tabindex="-1" aria-labelledby="deleteModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">supprimer le plat</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="modalBody">
      </div>
      <div class="modal-footer" id="modalFooter">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btnCancel">Annuler</button>
        <button type="button" class="btn btn-danger" id="deleteButtonModale" data-id="">Supprimer</button>
      </div>
    </div>
  </div>
</div>

<script src="../../src/views/admin/food/js/index.js"></script>
<script src="../../src/views/admin/food/js/remove.js"></script>
<?php

$content = ob_get_clean();

require_once('../src/views/admin/layout.php');
