<?php

ob_start();
?>

<div class="container">
  <h1>Catégorie</h1>
  <div class="row">
    <div class="col-lg-5">
      <ul class="list-group list-group-light">
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <span class="fw-bold mx-3" data-category-id="id">John Doe</span>
          <button type="button" class="btn btn-danger btn-sm showModal" data-bs-toggle="modal" data-bs-target="#exampleModal">supprimer</button>
        </li>
      </ul>

      <!-- Modal -->
      <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Supprimer la catégorie</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">annuler</button>
              <button type="button" class="btn btn-danger" id="deleteButtonCategory">supprimer</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

<script src="/../src/views/admin/category/js/delete.js"></script>

<?php

$content = ob_get_clean();

require_once('../src/views/admin/layout.php');
