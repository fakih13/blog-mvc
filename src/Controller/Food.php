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
    require_once('../src/views/admin/food/remove.php');
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
        echo $addRecipe['message'];
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
        // Récupérer l'ID de la recette à supprimer depuis la requête POST
        $idRecipe = $_POST["id"];
        $productModel = new FoodModel();
        //$deleteRecipe = $productModel->removeRecipe($idRecipe);
        $deleteRecipe = array(
          'success' => true,
          'message' => 'Le plat a été supprimée avec succès.'
        );
        echo json_encode($deleteRecipe);
        die;
        if ($deleteRecipe['success'] === true) {
          $response = array(
            'success' => true,
            'message' => 'Le plat a été supprimée avec succès.'
          );
        } else {
          $response = array(
            'success' => false,
            'message' => "La recette n'a pas été supprimée, veuillez réessayer."
          );
        }
      } else {
        $response = array(
          'success' => false,
          'message' => 'ID de recette manquant dans la requête POST.'
        );
      }

      // Envoyer la réponse JSON
      header('Content-Type: application/json');
      echo json_encode($response);
      exit;
    }
    //require_once('../src/views/admin/food/remove.php');
  }
  public function updateMeal()
  {
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
}
