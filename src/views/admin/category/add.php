<?php

ob_start();
?>

<h1>ajouter une catégorie</h1>

<form>
  <div class="input-group mb-3">
    <span class="input-group-text">nom</span>
    <input type="text" id="nameCategory" class="form-control someInput" placeholder="nom de la catégorie">
    <button type="submit" class="btn btn-primary btn-md">ajouter</button>
  </div>
</form>

<script src="/../src/views/admin/category/js/add.js"></script>
<?php

$content = ob_get_clean();

require_once('../src/views/admin/layout.php');
