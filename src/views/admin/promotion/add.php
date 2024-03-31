<?php

ob_start();
?>

<h1>ajouter une promtion</h1>

<form class="d-flex flex-column">
  <input type="text" name="name" id="name" placeholder="nom" class="mb-2">
  <select name="method" id="method" class="mb-2">
    <option value="automatic">Automatique</option>
    <option value="code">code de réduction</option>
  </select>


  <select name="target_type" id="targetType" class="mb-2">
    <option value="boutique">boutique</option>
    <option value="categorie">categorie</option>
    <option value="plat">plat</option>
  </select>
  <!-- ici mettre la liste pour choisir le produit ou la catégorie -->
  <input type="text" name="targetSearch" id="targetSearch" placeholder="chercher" class="mb-2">
  <div id="resultContainer"></div>

  <!--  -->

  <input type="number" name="percentage" id="percentage" placeholder="pourcentage" class="mb-2">
  <label for="start_date">Date de début</label>
  <div id="date" class="mb-2">
    <input type="date" name="start_date" id="start_date">
    <label for="addEnd">Ajouter une date de fin</label>
    <input type="checkbox" name="addEnd" id="addEnd">
  </div>

  <!-- afficher si add_end est checked -->

  <!--  -->
  <button type="submit">Ajouter</button>
</form>


<!-- <form>
  <span>réduction boutique</span>
  <input type="text">
  <select name="method" id="method">
    <option value="automatic">Automatique</option>
    <option value="code">code de réduction</option>
  </select>
  <input type="hidden" name="target_type" id="taget_type" value="boutique">
</form> -->

<script src="/src/views/admin/promotion/js/add.js"></script>

<?php

$content = ob_get_clean();

require_once('../src/views/admin/layout.php');
