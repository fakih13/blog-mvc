<?php

namespace App\Controller;

use App\Model\FoodModel;

class Food
{
  public function home()
  {
    require_once('../src/views/admin/food/index.php');
  }
  public function addMeal()
  {

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $errors = [];
      echo "post";
      $counter = 0;
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

      /* var_dump($ingredientRecipes); */

      $productModel = new FoodModel();
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
      if (!empty($newIngredients)) { // Vérification si $newIngredients n'est pas vide avant de le var_dump
        echo "j'enregistre";
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

      $postdata['Nom'] = /* $_POST['Nom']; */ 'Couscous';
      $postdata['Description'] = /* $_POST['Description']; */ "Dégustez notre délicieux couscous marocain : semoule légère, légumes frais et viande tendre, le tout sublimé par des épices authentiques. Un voyage de saveurs en seulement une bouchée !";
      $postdata['Prix'] = /* $_POST['Prix']; */ 15;
      $postdata['Ingredients'] = $ingredientRecipes;
      /* var_dump($postdata['Ingredients']); */
      $productModel->setRecipe($postdata);

      // enregistrement des inregidents liées au plat dans la table platingredient
    }
    require_once('../src/views/admin/food/add.php');
  }


  public function removeMeal()
  {
    require_once('../src/views/admin/food/remove.php');
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
