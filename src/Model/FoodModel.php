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
   * @param array $postData
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

          // Boucle pour ajouter les PlatID et IngredientID dans platIngredient
          foreach ($postData['Ingredients'] as $ingredientID) {
            $addIngredientQuery = "INSERT INTO `platingredient`(`PlatID`, `IngredientID`) VALUES (:PlatID, :IngredientID)";
            $addIngredientStatement = $connexion->prepare($addIngredientQuery);

            $addIngredientStatement->bindParam(':PlatID', $platID);
            $addIngredientStatement->bindParam(':IngredientID', $ingredientID);

            $addIngredientStatement->execute();

            // Vérifier si l'insertion a échoué
            if ($addIngredientStatement->rowCount() <= 0) {
              throw new \Exception('Erreur lors de l\'enregistrement des ingrédients');
            }
          }

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

  public function updateRecipe($recipeID, $postData)
  {
    try {
      $connexion = $this->database->dbConnect();
      $connexion->beginTransaction(); // Début de la transaction

      // Mettre à jour les informations principales du plat
      $updateMealInSql = "UPDATE `plat` SET `Nom` = :Nom, `Prix` = :Prix, `Description` = :Description WHERE `PlatID` = :PlatID";
      $updateStatement = $connexion->prepare($updateMealInSql);

      $updateStatement->bindParam(':PlatID', $recipeID);
      $updateStatement->bindParam(':Nom', $postData['Nom']);
      $updateStatement->bindParam(':Description', $postData['Description']);
      $updateStatement->bindParam(':Prix', $postData['Prix']);

      $updateStatement->execute();

      // Vérifier si la mise à jour principale a réussi
      if ($updateStatement->rowCount() > 0) {

        // Supprimer les anciens ingrédients liés au plat si nécessaire
        if (isset($postData['DeletedIngredients']) && is_array($postData['DeletedIngredients']) && !empty($postData['DeletedIngredients'])) {
          foreach ($postData['DeletedIngredients'] as $deletedIngredientID) {
            $deleteIngredientSql = "DELETE FROM `platingredient` WHERE `PlatID` = :PlatID AND `IngredientID` = :IngredientID";
            $deleteIngredientStatement = $connexion->prepare($deleteIngredientSql);
            $deleteIngredientStatement->bindParam(':PlatID', $recipeID);
            $deleteIngredientStatement->bindParam(':IngredientID', $deletedIngredientID);
            $deleteIngredientStatement->execute();
          }
        }

        // Ajouter les nouveaux ingrédients liés au plat si nécessaire
        if (isset($postData['AddedIngredients']) && is_array($postData['AddedIngredients']) && !empty($postData['AddedIngredients'])) {
          foreach ($postData['AddedIngredients'] as $ingredientID) {
            $addIngredientQuery = "INSERT INTO `platingredient`(`PlatID`, `IngredientID`) VALUES (:PlatID, :IngredientID)";
            $addIngredientStatement = $connexion->prepare($addIngredientQuery);

            $addIngredientStatement->bindParam(':PlatID', $recipeID);
            $addIngredientStatement->bindParam(':IngredientID', $ingredientID);

            $addIngredientStatement->execute();

            // Vérifier si l'insertion a échoué
            if ($addIngredientStatement->rowCount() <= 0) {
              throw new \Exception('Erreur lors de la mise à jour des ingrédients');
            }
          }
        }

        // Valider la transaction
        $connexion->commit();

        $response['success'] = true;
        $response['message'] = 'Mise à jour réussie';
      } else {
        throw new \Exception('Erreur lors de la mise à jour du plat');
      }
    } catch (\Exception $e) {
      // En cas d'erreur, annuler la transaction
      $connexion->rollBack();
      $response['success'] = false;
      $response['message'] = $e->getMessage();
    }

    return $response;
  }

  public function removeRecipe($id)
  {
    try {
      $deleteAnInSql = "DELETE FROM `plat` WHERE PlatID = :PlatID";
      $connexion = $this->database->dbConnect();
      $statement = $connexion->prepare($deleteAnInSql);
      $statement->bindParam(':PlatID', $id);

      $affectedRows = $statement->rowCount();

      if ($affectedRows > 0) {
        $response['success'] = true;
        $response['message'] = "Plat supprimée avec succès";
      } else {
        throw new \Exception('Erreur lors de la suppresion');
      }
    } catch (\Exception $e) {
      $response['message'] = $e->getMessage();
    }
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
