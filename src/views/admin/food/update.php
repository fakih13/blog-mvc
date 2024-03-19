<?php

ob_start();
?>

<h1>Plats Pti' Riad</h1>

<p>Modifier</p>


<?php if (!empty($errors)) : ?>
  <div>
    <?php foreach ($errors as $error) : ?>
      <div class="alert alert-danger" role="alert"><?= $error ?></div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>


<?php if (isset($results)) : ?>
  <form action="" method="post" class="d-flex flex-column w-50" id="myForm">
    <label for="Nom">Nom du plat</label>
    <input type="text" name="Nom" id="Nom" value="<?= $results['recipe'][0]['Nom'] ?>">
    <label for="Prix">Prix</label>
    <input type="number" step="0.01" name="Prix" id="Prix" value="<?= $results['recipe'][0]['Prix'] ?>">
    <label for="Description">Description</label>
    <textarea name="Description" id="Description" cols="30" rows="10"><?= $results['recipe'][0]['Description'] ?></textarea>
    <label for="ingredient">Ingrédient</label>
    <input type="text" name="ingredientSearch" id="ingredientSearch">
    <div id="resultSearch"></div>
    <div id="containerIngredient">
      <?php foreach ($results['ingredient'] as $ingredient) : ?>
        <div class="d-flex my-2">
          <input type="text" class="mx-2" name="ingredientRecipe_<?= $ingredient['IngredientID'] ?>" id="<?= $ingredient['IngredientID'] ?>" value="<?= $ingredient['Nom'] ?>" readonly>
          <button class="btn btn-danger btnIngredient" data-ingredient="<?= $ingredient['IngredientID'] ?>" data-recipe="<?= $results['id'] ?>">x</button>
        </div>
      <?php endforeach ?>
    </div>
    <button type="submit" class="mt-3">Modifier</button>
  </form>
<?php endif ?>
<script src="/../../src/views/admin/food/js/search.js"></script>
<script src="/../../src/views/admin/food/js/update.js"></script>

<?php

$content = ob_get_clean();
require_once('../src/views/admin/layout.php');
