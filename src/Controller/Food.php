<?php

namespace App\Controller;

use App\Model\FoodModel;

class Food
{
  public function home()
  {
    //require_once('../src/views/admin/food/index.php');
    $productModel = new FoodModel();
    $results = $productModel->getRecipes();
    require_once('../src/views/admin/food/index.php');
  }
  public function addMeal()
  {

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $productModel = new FoodModel();
      $errors = [];
      $ingredientRecipes = [];
      $counter = 0;
      while (isset($_POST['ingredientRecipe_' . $counter])) {
        $ingredient = array(
          'Nom' => $_POST['ingredientRecipe_' . $counter],
          'IngredientID' => null // Initialiser IngredientID à null
        );
        $ingredientRecipes[] = $ingredient;
        $counter++;
      }



      $newIngredients = []; // Initialisation de $newIngredients
      foreach ($ingredientRecipes as &$ingredient) { // Notez le & pour faire référence à la valeur réelle
        $result = $productModel->searchIngredient($ingredient['Nom']);
        if (empty($result)) {
          $newIngredients[] = $ingredient['Nom'];
        } else {
          foreach ($result as $item) {
            $ingredient['IngredientID'] = $item['IngredientID'];
          }
        }
      }
      unset($ingredient); // Dissociez la référence de $ingredient

      // enregistrement des nouveaux ingrédient dans la table ingredient
      if (!empty($newIngredients)) {
        foreach ($newIngredients as $saveIngredient) {
          $registerAnIngredient =  $productModel->setIngredient($saveIngredient);
          if ($registerAnIngredient['success'] !== true) {
            $errors[] = $registerAnIngredient['message'];
            break;
          } else {
            //$registerAnIngredient['ingredientId']
            // je recherche dans le tableau $IngredientRecipes la valeur pour y ajouter le nouvel id
            foreach ($ingredientRecipes as $index => $ingredient) {
              if ($ingredient['Nom'] === $saveIngredient) {
                $ingredientRecipes[$index]['IngredientID'] = $registerAnIngredient['ingredientId'];
              }
            }
          }
        }
      }

      // enregistrement du plat dans la table plat (nom, prix, description)

      $postdata['Nom'] = $_POST['Nom'];
      $postdata['Description'] = $_POST['Description'];
      $postdata['Prix'] = $_POST['Prix'];
      $postdata['Ingredients'] = $ingredientRecipes;
      $addRecipe = $productModel->setRecipe($postdata);
      if ($addRecipe['success'] === true) {
        header('location: /admin/food');
      }
    }
    require_once('../src/views/admin/food/add.php');
  }


  public function removeMeal()
  {
    //$productModel = new FoodModel();
    //$results = $productModel->getRecipes();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Vérifier si l'ID de la recette est passé dans la requête POST
      if (isset($_POST["id"])) {
        $idRecipe = $_POST["id"];
        $productModel = new FoodModel();
        $deleteRecipe = $productModel->removeRecipe($idRecipe);
        if ($deleteRecipe['success'] === true) {
          $response = array(
            'success' => true,
            'message' => 'Le plat a été supprimé avec succès.'
          );
        } else {
          $response = array(
            'success' => false,
            'message' => "Le plat n'a pas été supprimé, veuillez réessayer."
          );
        }
      } else {
        $response = array(
          'success' => false,
          'message' => 'ID de plat manquant dans la requête POST.'
        );
      }

      // Envoyer la réponse JSON
      header('Content-Type: application/json');
      echo json_encode($response);
      exit;
    } else {
      header('location: /admin/food');
    }
  }


  private function IngredientInSQL($ingredientPostValue, $ingredientRecipes)
  {
    $productModel = new FoodModel();


    $ingredient = array(
      'Nom' => $ingredientPostValue,
      'IngredientID' => null // Initialiser IngredientID à null
    );
    $ingredientRecipes[] = $ingredient;


    $result = $productModel->searchIngredient($ingredient['Nom']);
    if (!empty($result)) {
      foreach ($result as $item) {
        $ingredient['IngredientID'] = $item['IngredientID'];
        return $ingredientRecipes;
      }
    } else {
      return false;
    }
  }

  private function RegisteringAnIngredientInSQL($ingredientPostValue, $ingredientRecipes)
  {
    $productModel = new FoodModel();
    if (!empty($newIngredients)) {

      $registerAnIngredient = $productModel->setIngredient($ingredientPostValue);
      if ($registerAnIngredient['success'] !== true) {
        $errors[] = $registerAnIngredient['message'];
        return;
      } else {
        $ingredient = array(
          'Nom' => $ingredientPostValue,
          'IngredientID' => null // Initialiser IngredientID à null
        );
        $ingredient['IngredientID'] = $registerAnIngredient['ingredientId'];
        $ingredientRecipes[] = $ingredient;
        return $ingredientRecipes;
      }
    }
  }


  public function updateMeal($id)
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $counter = 0;
      $ingredientinRecipes = [];
      while (isset($_POST['ingredientRecipe_' . $counter])) {
        $ingredientSQL = $this->IngredientInSQL($_POST['ingredientRecipe_' . $counter], $ingredientinRecipes);
        if ($ingredientSQL === false) {
          $this->RegisteringAnIngredientInSQL($_POST['ingredientRecipe_' . $counter], $ingredientinRecipes);
        }
        $counter++;
      }


      header('Content-Type: application/json');
      echo json_encode($ingredientinRecipes);
      return;
    }
    $query = urldecode($id);
    $productModel = new FoodModel();
    $results = $productModel->getRecipes($id);
    require_once('../src/views/admin/food/update.php');
  }
  public function searchIngredient($q)
  {
    $query = urldecode($q);
    header('Content-Type: application/json');
    $productModel = new FoodModel();
    $results = $productModel->searchIngredient($query);
    if ($results) {
      echo json_encode($results);
      return;
    }
    echo json_encode([]);
    return;
  }

  /**
   * Supprime un ingrédient d'un plat dans la base de données.
   *
   * Cette méthode est destinée à être appelée par une requête HTTP POST.
   * Elle prend deux paramètres : l'identifiant du plat et l'identifiant de l'ingrédient à supprimer.
   *
   * @param string $id L'identifiant du plat.
   * @param string $idIngredient L'identifiant de l'ingrédient à supprimer du plat.
   * @return void Cette méthode renvoie une réponse JSON indiquant le succès ou l'échec de la suppression.
   *              En cas de succès, elle renvoie également les détails de la suppression.
   */
  public function removeMealIngredient($id, $idIngredient)
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $queryId = urldecode($id);
      $queryidIngredient = urldecode($idIngredient);

      $productModel = new FoodModel();

      // Appel de la fonction pour supprimer l'ingrédient du plat dans le modèle
      $deleteFromSql = $productModel->removeIngredientFromRecipe($queryId, $queryidIngredient);

      // Récupération du code de réponse HTTP
      $httpResponseCode = http_response_code();

      // Définition du type de contenu de la réponse comme JSON
      header('Content-Type: application/json');

      // Traitement de la réponse en fonction du code de réponse HTTP
      if ($httpResponseCode === 200 && $deleteFromSql['success']) {
        // La suppression a réussi, retourne le résultat de la suppression
        echo json_encode($deleteFromSql);
      } elseif ($httpResponseCode === 404) {
        // Aucune entrée trouvée pour la suppression, retourne un message d'erreur approprié
        echo json_encode(['success' => false, 'message' => 'Aucune entrée trouvée pour la suppression']);
      } else {
        // Retourne un message d'erreur générique pour les autres erreurs
        echo json_encode(['success' => false, 'message' => 'Une erreur s\'est produite lors de la suppression de l\'ingrédient.']);
      }
    } else {
      // Requête invalide, retourne un message d'erreur indiquant une méthode de requête non autorisée
      echo json_encode(['success' => false, 'message' => 'Méthode de requête non autorisée.']);
    }
  }
}
