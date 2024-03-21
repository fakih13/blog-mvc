<?php

namespace App\Model;

use App\Lib\Database;

class FoodModel
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
   * @param string $postData
   * @return boolean  
   */
  public function setIngredient($postData)
  {
    try {
      $savingAnIngredientInSql = "INSERT INTO `ingredient`(`Nom`) VALUES (:Nom)";
      $connexion = $this->database->dbConnect();
      $statement = $connexion->prepare($savingAnIngredientInSql);

      $statement->bindParam(':Nom', $postData);

      $statement->execute();

      if ($statement->rowCount() > 0) {
        $response['success'] = true;
        $response['message'] = 'Enregistrement réussi';
        $response['ingredientId'] = $connexion->lastInsertId();
      } else {
        throw new \Exception("Erreur lors de l'enregistrement de l'ingrédient " . $postData);
      }
    } catch (\Exception $e) {
      $response['message'] = $e->getMessage();
    }
    return $response;
  }
  public function removeIngredient($id)
  {
    try {
      $deleteAnInSql = "DELETE FROM `ingredient` WHERE IngredientID = :id";
      $connexion = $this->database->dbConnect();
      $statement = $connexion->prepare($deleteAnInSql);
      $statement->bindParam(':id', $id);

      $affectedRows = $statement->rowCount();

      if ($affectedRows > 0) {
        $response['success'] = true;
        $response['message'] = "Entrée supprimée avec succès";
      } else {
        throw new \Exception('Erreur lors de la suppresion');
      }
    } catch (\Exception $e) {
      $response['message'] = $e->getMessage();
    }
  }
  /**
   * Récupère les recettes depuis la table 'plat' dans la base de données.
   * 
   * @return array|false Un tableau contenant les recettes si des recettes sont trouvées, sinon false.
   */
  public function getRecipes($id = null)
  {
    try {
      $connexion = $this->database->dbConnect();
      if ($id !== null) {
        $getMealInSql = "SELECT * FROM `plat` WHERE PlatID = :id";
        $statement = $connexion->prepare($getMealInSql);
        $statement->bindParam(':id', $id);
        $statement->execute();
        $success = $statement->fetchAll(\PDO::FETCH_ASSOC);
        if ($success) {
          $getIngredientInSql = "SELECT i.Nom, i.IngredientID
          FROM ingredient i
          JOIN platingredient pi ON i.IngredientID = pi.IngredientId
          WHERE pi.PlatId = :id;
          ";
          $stmt = $connexion->prepare($getIngredientInSql);
          $stmt->bindParam(':id', $id);
          $stmt->execute();
          $ingredient = $stmt->fetchAll(\PDO::FETCH_ASSOC);
          if ($ingredient) {
            $response['ingredient'] = $ingredient;
            $response['id'] = $id;
          } else {
            throw new \Exception('no content');
            $response['code'] = 500;
          }
        }
      } else {
        $getMealInSql = "SELECT * FROM `plat`";
        $statement = $connexion->prepare($getMealInSql);
        $statement->execute();
        $success = $statement->fetchAll(\PDO::FETCH_ASSOC);
      }

      if ($success) {
        $response['success'] = true;
        $response['code'] = 200;
        $response['recipe'] = $success;
      } else {
        $response['code'] = 204;
        throw new \Exception('no content');
      }
    } catch (\Exception $e) {
      $response['success'] = false;
      $response['message'] = $e->getMessage();
    }

    return $response;
  }

  /**
   * @param array $postData
   * @return boolean  
   */
  public function setRecipe($postData)
  {
    try {
      $connexion = $this->database->dbConnect();

      // Début de la transaction
      $connexion->beginTransaction();

      try {
        $savingMealInSql = "INSERT INTO `plat`(`Nom`, `Prix`, `Description`) VALUES (:Nom, :Prix, :Description)";
        $statement = $connexion->prepare($savingMealInSql);

        $statement->bindParam(':Nom', $postData['Nom']);
        $statement->bindParam(':Description', $postData['Description']);
        $statement->bindParam(':Prix', $postData['Prix']);

        $statement->execute();

        if ($statement->rowCount() > 0) {
          // Récupérer l'identifiant du dernier enregistrement inséré
          $platID = $connexion->lastInsertId();

          $saveIngredientInRecipes = $this->newIngredientRecipes($postData['Ingredients'], $platID);

          // Si tout s'est bien passé, valide la transaction
          $connexion->commit();

          $response['success'] = true;
          $response['message'] = 'Enregistrement réussi';
          $response['platID'] = $platID;
        } else {
          throw new \Exception('Erreur lors de l\'enregistrement du plat');
        }
      } catch (\Exception $e) {
        // En cas d'erreur, annule la transaction
        $connexion->rollBack();
        throw $e; // Renvoie l'exception pour que l'appelant puisse la gérer
      }
    } catch (\Exception $e) {
      $response['success'] = false;
      $response['message'] = $e->getMessage();
    }

    return $response;
  }
  public function newIngredientRecipes($getIngredientInRecipes, $platID)
  {
    $connexion = $this->database->dbConnect();
    foreach ($getIngredientInRecipes as $ingredient) {
      $addIngredientQuery = "INSERT INTO `platingredient`(`PlatID`, `IngredientID`) VALUES (:PlatID, :IngredientID)";
      $addIngredientStatement = $connexion->prepare($addIngredientQuery);

      $addIngredientStatement->bindParam(':PlatID', $platID);
      $addIngredientStatement->bindParam(':IngredientID', $ingredient['IngredientID']);

      $addIngredientStatement->execute();

      // Vérifier si l'insertion a échoué
      if ($addIngredientStatement->rowCount() <= 0) {
        throw new \Exception('Erreur lors de l\'enregistrement des ingrédients');
      } else {
        return true;
      }
    }
  }
  public function updateRecipe($recipeID, $modifiedData)
  {
    $response = array();

    try {
      $connexion = $this->database->dbConnect();

      // Début de la transaction
      $connexion->beginTransaction();

      try {
        // Construction de la requête de mise à jour
        $updateMealSql = "UPDATE `plat` SET ";
        $updateValues = array();

        foreach ($modifiedData as $key => $value) {
          // Ajouter chaque champ modifié à la requête de mise à jour
          $updateMealSql .= "`$key` = :$key, ";
          $updateValues[":$key"] = $value;
        }

        // Supprimer la virgule et l'espace en trop à la fin de la requête
        $updateMealSql = rtrim($updateMealSql, ', ');

        // Ajouter la clause WHERE
        $updateMealSql .= " WHERE `PlatID` = :PlatID";
        $updateValues[':PlatID'] = $recipeID;

        // Préparation de la requête
        $updateStatement = $connexion->prepare($updateMealSql);

        // Liaison des valeurs
        foreach ($updateValues as $key => &$value) {
          $updateStatement->bindParam($key, $value);
        }

        // Exécution de la requête
        $updateStatement->execute();

        // Si la mise à jour a réussi, valider la transaction
        $connexion->commit();

        $response['success'] = true;
        $response['message'] = 'Mise à jour réussie';
      } catch (\Exception $e) {
        // En cas d'erreur, annuler la transaction et renvoyer l'erreur
        $connexion->rollBack();
        $response['success'] = false;
        $response['message'] = $e->getMessage();
      }
    } catch (\Exception $e) {
      $response['success'] = false;
      $response['message'] = $e->getMessage();
    }

    return $response;
  }

  /**
   * Supprime un ingrédient d'une recette dans la base de données.
   *
   * @param int $idRecipe L'identifiant de la recette.
   * @param int $idIngredient L'identifiant de l'ingrédient à supprimer de la recette.
   * @return array Un tableau contenant une indication sur le succès de l'opération
   *               et éventuellement un message d'erreur en cas d'échec.
   */
  public function removeIngredientFromRecipe($idRecipe, $idIngredient)
  {
    try {
      $deleteAnInSql = "DELETE FROM `platingredient` WHERE IngredientID = :idIngredient AND PlatId = :idRecipe;";
      $connexion = $this->database->dbConnect();
      $statement = $connexion->prepare($deleteAnInSql);
      $statement->bindParam(':idRecipe', $idRecipe);
      $statement->bindParam(':idIngredient', $idIngredient);
      $statement->execute();
      $affectedRows = $statement->rowCount();

      if ($affectedRows > 0) {
        // Renvoyer un code HTTP 200 (OK) et un message si la suppression réussit
        http_response_code(200);
        return ['success' => true, 'message' => "Ingrédient supprimé avec succès"];
      } else {
        // Renvoyer un code HTTP 404 (Non trouvé) et un message si aucune ligne n'a été affectée
        http_response_code(404);
        return ['success' => false, 'message' => 'Aucune entrée trouvée pour la suppression'];
      }
    } catch (\Exception $e) {
      // Renvoyer un code HTTP 500 (Erreur interne du serveur) et un message d'erreur en cas d'exception
      http_response_code(500);
      return ['success' => false, 'message' => $e->getMessage()];
    }
  }


  public function removeRecipe($id)
  {
    try {
      $response['success'] = false;
      $deleteAnInSql = "DELETE FROM `plat` WHERE PlatID = :PlatID";
      $connexion = $this->database->dbConnect();
      $statement = $connexion->prepare($deleteAnInSql);
      $statement->bindParam(':PlatID', $id);
      $statement->execute();

      $affectedRows = $statement->rowCount();

      if ($affectedRows > 0) {
        $response['success'] = true;
      } else {
        throw new \Exception('Erreur lors de la suppresion');
      }
    } catch (\Exception $e) {
      $response['message'] = $e->getMessage();
    }
    return $response;
  }
  public function searchIngredient($name)
  {
    $request = false;
    $data = [];
    $sql = 'SELECT * FROM ingredient WHERE Nom LIKE :name';
    $connexion = $this->database->dbConnect();
    $statement = $connexion->prepare($sql);
    $name = "%$name%";
    $statement->bindParam(':name', $name);
    $statement->execute();
    $success = $statement->fetchAll(\PDO::FETCH_ASSOC);
    if ($success) {
      $request = true;
      $data = $success;
      return $data;
    }
    return $data;
  }
}
