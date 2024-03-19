<?php

ob_start();
?>

<h1>Plats Pti' Riad</h1>

<p>ajouter</p>


<?php if (!empty($errors)) : ?>
  <div>
    <?php foreach ($errors as $error) : ?>
      <div class="alert alert-danger" role="alert"><?= $error ?></div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>



<form action="" method="post" class="d-flex flex-column w-50" id="myForm">
  <label for="Nom">Nom du plat</label>
  <input type="text" name="Nom" id="Nom">
  <label for="Prix">Prix</label>
  <input type="number" step="0.01" name="Prix" id="Prix">
  <label for="Description">Description</label>
  <textarea name="Description" id="Description" cols="30" rows="10"></textarea>
  <label for="ingredient">Ingrédient</label>
  <input type="text" name="ingredientSearch" id="ingredientSearch">
  <div id="resultSearch"></div>
  <div id="containerIngredient"></div>
  <button type="submit" class="mt-3">Ajouter</button>
</form>
<script src="../../src/views/admin/food/js/search.js"></script>

<?php

$content = ob_get_clean();
require_once('../src/views/admin/layout.php');
