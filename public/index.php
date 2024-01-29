<?php


define('MY_APP_STARTED', true);
require_once __DIR__ . '/../vendor/autoload.php';


use App\Lib\Router;

$router = new Router();

$router->add('/', 'Index@home');
$router->add('/login/admin', 'Admin@login');
$router->add('/disconnect', 'Admin@disconnect');
$router->add('/admin', 'Admin@index');
$router->add('/admin/setPost', 'Admin@setpost');
$router->add('/admin/testEditor', 'Admin@testEditor');


$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$router->dispatch($url);
