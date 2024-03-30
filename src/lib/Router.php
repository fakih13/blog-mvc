<?php

namespace App\Lib;

class Router
{
    private $routes = [];

    public function add($route, $action)
    {
        $this->routes[$route] = $action;
    }

    public function dispatch($url)
    {
        session_start(); // Assurez-vous que la session est démarrée
        $_SESSION['ADMIN_EMAIL'] = 'madi.bch@gmail.com';
        $_SESSION['ADMIN_FIRSTNAME'] = 'madi';
        $_SESSION['ADMIN_LASTNAME'] = 'fakih';
        foreach ($this->routes as $route => $action) {
            $routePattern = "#^" . preg_replace('/\{([\w]+)\}/', '([^/]+)', $route) . "$#";
            if (preg_match($routePattern, $url, $matches)) {
                array_shift($matches); // Retirer l'URL complète des correspondances
                list($controller, $method) = explode('@', $action);

                // Vérification de la session pour les routes admin
                if (strpos($route, '/admin') === 0 && !isset($_SESSION['ADMIN_EMAIL'])) {
                    header("Location: /login/admin"); // Redirige vers la page de login
                    return;
                }

                $controller = $this->buildControllerNamespace($controller);
                if (class_exists($controller)) {
                    $controllerObj = new $controller();
                    call_user_func_array([$controllerObj, $method], $matches);
                    return;
                }
            }
        }

        header("HTTP/1.0 404 Not Found");
        echo '404 Not Found';
    }

    private function buildControllerNamespace($controller)
    {
        // Ajoutez la logique pour gérer le sous-dossier 'public'
        if (strpos($controller, 'PublicArea\\') === 0) {
            // Si le contrôleur est dans le sous-dossier 'public'
            return "App\\Controller\\PublicArea\\" . substr($controller, 11); // Retirer 'Public\\'
        } else {
            // Pour les contrôleurs dans le dossier 'Controller' sans sous-dossier
            return "App\\Controller\\" . $controller;
        }
    }
}
