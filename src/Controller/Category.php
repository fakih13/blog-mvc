<?php

namespace App\Controller;

use App\Model\CategoryModel;


class Category
{

  protected $model;

  public function __construct()
  {
    $this->model = new CategoryModel();
  }

  public function display()
  {
    require_once('../src/views/admin/category/index.php');
  }


  public function addDisplay()
  {
    require_once('../src/views/admin/category/add.php');
  }

  public function add()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $json = file_get_contents('php://input');

      // Décoder le JSON en tableau associatif
      $data = json_decode($json, true);

      // Vérifier si des données ont été décodées
      header('Content-Type: application/json');
      if ($data !== null) {
        // Faire quelque chose avec les données reçues
        // Par exemple, afficher les données
        $saveInSql = $this->model->saveACategoryInTheDatabase($data);
        echo json_encode($saveInSql);
      } else {
        // Si le décodage JSON a échoué
        http_response_code(400); // Bad Request
        echo json_encode(array("error" => "Invalid JSON data"));
      }
    }
    /* $post = $_POST['nom'];
    $addInSql = $this->model->saveACategoryInTheDatabase($post);
    if ($addInSql['success']) {
      # code...
    } else {
      http_response_code(500);
      $message = $addInSql['message'];
    } */
  }

  public function delete($id)
  {
    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
      $idCategory = urldecode($id);
      header('Content-Type: application/json');
      $deleteInSql = $this->model->deleteCategoryFromDatabase($id);
      echo json_encode($deleteInSql);
    }
  }
}
