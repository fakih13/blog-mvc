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
      while (isset($_POST['ingredientRecipe_' . $counter])) {
        $ingredientRecipes[] = $_POST['ingredientRecipe_' . $counter];
        $counter++;
      }
      /* var_dump($ingredientRecipes); */

      $productModel = new FoodModel();
      $newIngredients = []; // Initialisation de $newIngredients
      foreach ($ingredientRecipes as $ingredient) {
        $result = $productModel->searchIngredient($ingredient);
        if (empty($result)) {
          $newIngredients[] = $ingredient;
        }
      }

      // enregistrement des nouveaux ingrédient dans la table ingredient
      if (!empty($newIngredients)) { // Vérification si $newIngredients n'est pas vide avant de le var_dump
        echo "j'enregistre";
        foreach ($newIngredients as $saveIngredient) {
          $registerAnIngredient =  $productModel->setIngredient($saveIngredient);
          if ($registerAnIngredient['success'] !== true) {
            $errors[] = $registerAnIngredient['message'];
            break;
          }
        }
      }

      // enregistrement du plat dans la table plat


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
