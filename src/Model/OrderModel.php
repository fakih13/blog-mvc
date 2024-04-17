<?php

namespace App\Model;

use App\Lib\Database;

class OrderModel
{
  /**
   * @var establish a PDO connection
   */
  protected $database;

  public function __construct()
  {
    $this->database = new Database();
  }

  public function getOrder()
  {
    try {
      $requestSql = "SELECT * from commande";
      $connexion = $this->database->dbConnect();
      $statement = $connexion->prepare($requestSql);
      $statement->execute();
      $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
      if ($data) {
        $response['data'] = $data;
      } else {
        $response['data'] = [];
      }
    } catch (\PDOException $e) {
      $response['message'] = $e->getMessage();
    }
    return $response;
  }
}
