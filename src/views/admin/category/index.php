<?php

ob_start();
?>

<h1>Catégorie</h1>

<div class="container">
  <div class="row">
    <div class="col-lg-5 col-8">
      <div class="list-group">
        <li class="list-group-item">
          <span class="me-6">test</span>
          <button class="btn btn-danger">supprimer</button>
        </li>
        <li class="list-group-item">rico</li>
        <li class="list-group-item">popo</li>
      </div>
    </div>
  </div>

</div>


<?php

$content = ob_get_clean();

require_once('../src/views/admin/layout.php');
