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
}
