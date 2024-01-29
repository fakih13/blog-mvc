<?php

namespace App\Controller;


class Admin
{
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            $_SESSION['ADMIN'] = $_POST['fakih'];
            header("Location: /admin", true, 301);
        }
        require_once('../src/views/admin/login.php');
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

    public function setpost()
    {
        require_once('../src/views/admin/setpost.php');
    }


    public function testEditor()
    {

        // Connexion à la base de données
        /* $dbHost = 'localhost';
        $dbUsername = 'root';
        $dbPassword = 'S/bAb8MTd)[NZj3P';
        $dbName = 'testblog';

        $conn = new \mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

        // Vérifier la connexion
        if ($conn->connect_error) {
            die("Échec de la connexion: " . $conn->connect_error);
        }

        // Récupérer le contenu et nettoyer pour éviter les injections SQL
        $content = $conn->real_escape_string($_POST['content']);

        // Requête SQL pour insérer le contenu
        $sql = "INSERT INTO `texteditor`(`text`) VALUES ('$content')";

        if ($conn->query($sql) === TRUE) {
            echo json_encode("Contenu enregistré avec succès");
        } else {
            echo json_encode("Erreur: " . $sql . "<br>" . $conn->error);
        }

        $conn->close(); */

        echo json_encode($_POST);
    }
}
