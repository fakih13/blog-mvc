<?php


define('MY_APP_STARTED', true);
require_once __DIR__ . '/../vendor/autoload.php';


use App\Lib\Router;

$router = new Router();

$router->add('/', 'Index@home');
$router->add('/login/admin', 'Admin@login');
$router->add('/register/admin', 'Admin@register');
$router->add('/disconnect', 'Admin@disconnect');
$router->add('/admin', 'Admin@index');
$router->add('/admin/newPost', 'Admin@newPost');
$router->add('/admin/setPost', 'Admin@setPost');
$router->add('/searchIngredient/{q}', 'Food@searchIngredient');

/* Food */
$router->add('/admin/food', 'Food@home');
$router->add('/admin/food/ajouter', 'Food@addMeal');
$router->add('/admin/food/supprimer', 'Food@removeMeal');
$router->add('/admin/food/update/view/{id}', 'Food@displayMealUpdateView');
$router->add('/admin/food/update/database/{id}', 'Food@updateMealInDatabase');
$router->add('/admin/food/update/removeIngredient/{idRecipe}/{idIngredient}', 'Food@removeMealIngredient');
/* $router->add('/admin/food/supprimer/{id}', 'Index@home'); */

/* Promotion */

$router->add('/admin/promotion/searchTarget/{target}/{q}', 'PromotionController@searchTarget');
$router->add('/admin/promotion', 'PromotionController@display');
$router->add('/admin/promotion/current', 'PromotionController@display');
$router->add('/admin/promotion/add/view', 'PromotionController@addDisplay');
$router->add('/admin/promotion/add/database', 'PromotionController@add');
$router->add('/admin/promotion/update/{id}', 'Promotion@update');
$router->add('/admin/promotion/remove/{id}', 'PromotionController@remove');



$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$router->dispatch($url);
