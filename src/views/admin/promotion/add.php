<?php

ob_start();
?>

<h1>ajouter une promtion</h1>

<form>
  <input type="text" name="name" id="name" placeholder="nom">
  <select name="method" id="method">
    <option value="automatic">Automatique</option>
    <option value="code">code de réduction</option>
  </select>


  <select name="target_type" id="tagetType">
    <option value="categorie">categorie</option>
    <option value="plat">plat</option>
  </select>
  <!-- ici mettre la liste pour choisir le produit ou la catégorie -->
  <input type="text" name="targetSearch" id="targetSearch" placeholder="chercher">
  <div id="resultContainer"></div>


  <div id="containerTargetChoice"></div>
  <!--  -->

  <input type="number" name="percentage" id="percentage" placeholder="pourcentage">
  <input type="date" name="start_date" id="start_date">
  <input type="checkbox" name="add_end" id="add_end">

  <!-- afficher si add_end est checked -->
  <input type="date" name="end_date" id="end_date">
  <!--  -->
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
