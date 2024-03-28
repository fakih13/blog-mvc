<?php

namespace App\Controller;

use App\Model\FoodModel;


class Promotion
{
  /**
   * Display the promotion page
   * @return void
   */
  public function display()
  {
    $result = 'ok';
    require_once('../src/views/admin/promotion/index.php');
    return;
  }

  public function add()
  {
    require_once('../src/views/admin/promotion/add.php');
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
      # code...
    }
  }

  public function remove()
  {
    echo 'ok';
  }

  public function update($id)
  {
    $query = urldecode($id);
  }

  public function searchTarget($target, $q)
  {
    $target = urldecode($target);
    $query = urldecode($q);


    if (isset($_SESSION['ADMIN_EMAIL'])) {
      /* $target !== "produit" || $target !== "categorie" */
      if ($target !== "plat" && $target !== "categorie") {
        http_response_code(400);
        echo "Erreur 400 : Mauvaise requête ";
      } else {
        $productModel = new FoodModel();
        $results = $productModel->searchTarget($target, $q);
        header('Content-Type: application/json');
        if ($results) {
          echo json_encode($results);
          return;
        }
        echo json_encode([]);
        return;
      }
    } else {
      http_response_code(400);
      echo "Erreur 400 : Mauvaise requête";
    }
  }
}
