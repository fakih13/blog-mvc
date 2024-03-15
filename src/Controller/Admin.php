<?php

namespace App\Controller;


use App\Model\AdminModel;

class Admin
{
    public function login()
    {
        /* 
        $test = '$2y$10$HENMMZy6OyDsIBIJxa8oM.n08';
        $testMDP = 'test';
        var_dump(password_verify($testMDP, $test));
        die;
        */
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            $fields = ['email', 'password'];
            $adminModel = new AdminModel();



            foreach ($fields as $field) {
                if (empty($_POST[$field])) {
                    // Ajouter une entrée dans le tableau $errors avec le champ comme clé et le message d'erreur comme valeur
                    $errors[$field] = "Le champ $field est requis et ne peut pas être vide.";
                }
            }
            if (!empty($errors)) {
                http_response_code(400);
                require_once('../src/views/admin/login.php');
                return; // Arrêter l'exécution de la fonction
            }
            $register = $adminModel->adminLogin($_POST);
            if ($register['success']) {
                $_SESSION['ADMIN_ID'] = $register['id'];
                $_SESSION['ADMIN_EMAIL'] = $register['email'];
                $_SESSION['ADMIN_FIRSTNAME'] = $register['firstName'];
                $_SESSION['ADMIN_LASTNAME'] = $register['lastName'];
                header("Location: /admin", true, 301);
                exit();
            } else {
                $errors['erreur base de données'] = $register['message'];
                http_response_code(400);
                require_once('../src/views/admin/login.php');
                return;
            }
        }
        require_once('../src/views/admin/login.php');
    }
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Liste des champs à vérifier
            $fields = ['firstName', 'lastName', 'phone', 'email', 'password', 'password2'];

            // Initialiser un tableau pour stocker les erreurs par champ
            $errors = [];

            // Parcourir chaque champ et vérifier s'il est vide
            foreach ($fields as $field) {
                if ($field === 'published') {
                    if ($_POST[$field] === "0" || $_POST[$field] === "1") {
                        continue;
                    } else {
                        $errors[$field] = "Le champ $field est requis et ne peut pas être vide.";
                        continue;
                    }
                }
                if (empty($_POST[$field])) {
                    // Ajouter une entrée dans le tableau $errors avec le champ comme clé et le message d'erreur comme valeur
                    $errors[$field] = "Le champ $field est requis et ne peut pas être vide.";
                }
            }

            if ($_POST['password'] !== $_POST['password2']) {
                $errors['password'] = "Les champs mots de passe sont différents.";
            }
            // Vérifier s'il y a des erreurs
            if (!empty($errors)) {
                http_response_code(400);
                require_once('../src/views/admin/register.php');
                return;
            }
            $adminModel = new AdminModel();
            $register = $adminModel->createAdmin($_POST);
            if ($register['success']) {
                // Stocker le message de succès dans la session
                session_start();
                $_SESSION['success_message'] = 'Compte créé avec succès';

                // Redirection vers la page de connexion
                header("Location: /login/admin");
                exit(); // Assurez-vous de terminer l'exécution du script après la redirection
            } else {
                $errors['erreur base de données'] = $register['message'];
                http_response_code(400);
                require_once('../src/views/admin/register.php');
                return;
            }
        }
        require_once('../src/views/admin/register.php');
    }
    public function disconnect()
    {
        session_destroy();
        header("Location: /login/admin", true, 301);
    }
    public function index()
    {
        require_once('../src/views/admin/index.php');
    }

    public function newPost()
    {
        require_once('../src/views/admin/setpost.php');
    }


    public function setPost()
    {
        // Liste des champs à vérifier
        $fields = ['Title', 'MetaTitle', 'Slug', 'published', 'summary', 'article'];

        // Initialiser un tableau pour stocker les erreurs par champ
        $errors = [];

        // Parcourir chaque champ et vérifier s'il est vide
        foreach ($fields as $field) {
            if ($field === 'published') {
                if ($_POST[$field] === "0" || $_POST[$field] === "1") {
                    continue;
                } else {
                    $errors[$field] = "Le champ $field est requis et ne peut pas être vide.";
                    continue;
                }
            }
            if (empty($_POST[$field])) {
                // Ajouter une entrée dans le tableau $errors avec le champ comme clé et le message d'erreur comme valeur
                $errors[$field] = "Le champ $field est requis et ne peut pas être vide.";
            }
        }

        // Vérifier s'il y a des erreurs
        if (!empty($errors)) {
            // S'il y a des erreurs, renvoyer les erreurs en JSON avec détail par champ
            http_response_code(400);
            echo json_encode(['error' => true, 'errors' => $errors]);
            return; // Arrêter l'exécution de la fonction
        }

        // Si tous les champs sont présents et non vides, traiter normalement la requête
        echo json_encode(['success' => true, 'data' => $_POST]);
    }
}
