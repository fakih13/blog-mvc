<?php

namespace App\Model;

use App\Lib\Database;


/**
 * class to manage post
 */

class PromotionModel
{

  /**
   * @var establish a PDO connection
   */
  protected $database;

  public function __construct()
  {
    $this->database = new Database();
  }


  /**
   * Add a new promotion post to the database.
   *
   * @param array $data The data to be inserted into the promotion table.
   * @return array An associative array indicating the success or failure of the operation.
   */
  public function addAPostInSql($data)
  {
    try {
      $savePromotionInSql = "INSERT INTO `promotion`(`method`, `target_type`, `target_id`, `percentage`, `name`, `start_date`, `end_date`, `status`) VALUES (:method, :target, :targetID, :percentage, :name, :start, :end, :status)";
      $connexion = $this->database->dbConnect();
      $connexion->beginTransaction();
      $statement = $connexion->prepare($savePromotionInSql);

      // Liaison des paramètres en fonction de la présence dans les données
      $statement->bindParam(':method', $data['method']);
      $statement->bindParam(':target', $data['target_type']);
      $statement->bindParam(':targetID', $data['PlatID']);
      $statement->bindParam(':percentage', $data['percentage']);
      $statement->bindParam(':name', $data['name']);
      $statement->bindParam(':start', $data['start_date']);

      // Vérification de la présence de la clé 'end' dans les données
      if (isset($data['end'])) {
        $statement->bindParam(':end', $data['end']);
      } else {
        $statement->bindValue(':end', null, \PDO::PARAM_NULL); // Utilisation de bindValue pour lier à NULL
      }

      $statut = 'progress'; // Je suppose que vous vouliez lier à 'status' et non à 'statut'
      $statement->bindParam(':status', $statut);

      // Exécution de la première requête
      $successPromotionInsertion = $statement->execute();

      // Vérification de la réussite de la première requête
      if (!$successPromotionInsertion) {
        throw new \Exception("Failed to insert promotion data");
      }

      $promoID = $connexion->lastInsertId();

      // Si la méthode est 'code', exécuter la deuxième requête
      if ($data['method'] === 'code') {
        $saveCodeInSql = "INSERT INTO `promotion_code`(`promotion_id`, `code`) VALUES (:promoID, :code)";
        $statementCode = $connexion->prepare($saveCodeInSql);
        $statementCode->bindParam(':promoID', $promoID);
        $statementCode->bindParam(':code', $data['code']);

        // Exécution de la deuxième requête
        $successCodeInsertion = $statementCode->execute();

        // Vérification de la réussite de la deuxième requête
        if (!$successCodeInsertion) {
          throw new \Exception("Failed to insert promotion code data");
        }
      }

      $connexion->commit();

      // Réponse en cas de succès
      $response['success'] = true;
      $response['message'] = "Data inserted successfully";
      return $response;
    } catch (\Exception $e) {
      $connexion->rollBack();
      $response['success'] = false;
      $response['message'] = $e->getMessage();
      return $response;
    }
  }
  /**
   * Retrieves information about a promotion based on the provided ID, or all promotions if no ID is specified.
   *
   * @param int|null $id The ID of the promotion to retrieve. Optional.
   * @return array The retrieved promotion data.
   * @throws \Exception If no content is found.
   */
  public function getPromo($id = null)
  {
    $response = [
      'success' => false,
      'code' => 204,
      'recipe' => null,
      'message' => ''
    ];

    try {
      $connexion = $this->database->dbConnect();
      $getPromotionInSql = ($id !== null) ? "SELECT promotion.*, 
      COALESCE(plat.Nom, categorie.nom) AS target_name
      FROM promotion
      LEFT JOIN plat ON promotion.target_type = 'plat' AND promotion.target_id = plat.PlatID
      LEFT JOIN categorie ON promotion.target_type = 'categorie' AND promotion.target_id = categorie.id
      WHERE promotion.id = :id;
" : "SELECT * FROM `promotion`";
      $statement = $connexion->prepare($getPromotionInSql);

      if ($id !== null) {
        $statement->bindParam(':id', $id);
      }

      $statement->execute();
      $data = $statement->fetchAll(\PDO::FETCH_ASSOC);

      if (!empty($data)) {
        $response['success'] = true;
        $response['code'] = 200;
        $response['promotion'] = $data;
      } else {
        $response['promotion'] = [];
      }
    } catch (\PDOException $e) {/* 
      $connexion->rollBack(); */
      $response['message'] = $e->getMessage();
    }

    return $response;
  }
}
