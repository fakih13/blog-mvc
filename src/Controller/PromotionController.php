<?php

namespace App\Controller;

use App\Model\FoodModel;
use App\Model\PromotionModel;

class PromotionController
{
  private $foodModel;
  private $promotionModel;

  public function __construct()
  {
    $this->foodModel = new FoodModel();
    $this->promotionModel = new PromotionModel();
  }

  /**
   * Displays the promotions page in the administration interface.
   * @return void
   */
  public function display()
  {
    $data = $this->promotionModel->getPromo();
    require_once('../src/views/admin/promotion/index.php');
  }
  /**
   * display the form to add the promotion and process the addition of the promotion in the database
   */
  public function addDisplay()
  {
    require_once('../src/views/admin/promotion/add.php');
  }

  public function add()
  {
    // Récupérer le contenu JSON de la requête
    $json = file_get_contents('php://input');

    // Décoder le JSON en tableau associatif
    $data = json_decode($json, true);

    // Vérifier si des données ont été décodées
    if ($data !== null) {
      // Faire quelque chose avec les données reçues
      // Par exemple, afficher les données
      $savePromotion = $this->promotionModel->addAPostInSql($data);
      header('Content-Type: application/json');
      echo json_encode($savePromotion);
    } else {
      // Si le décodage JSON a échoué
      http_response_code(400); // Bad Request
      echo json_encode(array("error" => "Invalid JSON data"));
    }
  }


  public function remove()
  {
    // Fonction de suppression d'une promotion
  }

  public function update($id)
  {
    $decodedId = urldecode($id);
    // Logique de mise à jour d'une promotion avec l'ID spécifié
  }

  public function searchTarget($target, $q)
  {
    $decodedTarget = urldecode($target);
    $decodedQuery = urldecode($q);

    if (isset($_SESSION['ADMIN_EMAIL'])) {
      header('Content-Type: application/json');
      if ($decodedTarget !== "plat" && $decodedTarget !== "categorie") {
        http_response_code(400);
        echo json_encode(["Erreur 400 : Mauvaise requête "]);
      } else {
        $results = $this->foodModel->searchTarget($decodedTarget, $decodedQuery);
        echo json_encode($results);
      }
    } else {
      http_response_code(400);
      echo json_encode(["Erreur 400 : Mauvaise requête "]);
    }
  }
}
