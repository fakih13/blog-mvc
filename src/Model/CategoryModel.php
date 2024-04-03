<?php

namespace App\Model;

use App\Lib\Database;


class CategoryModel
{
    /**
     * @var establish a PDO connection
     */
    protected $database;

    public function __construct()
    {
        $this->database = new Database();
    }

    public function saveACategoryInTheDatabase($data)
    {
        try {
            $sqlRequest = "INSERT INTO `categorie`(`nom`) VALUES (:nom)";

            // Préparation de la requête
            $connexion = $this->database->dbConnect();
            $statement = $connexion->prepare($sqlRequest);

            // Définir les valeurs pour chaque placeholder
            $statement->bindParam(':nom', $data);

            $statement->execute();
            if ($statement->rowCount() > 0) {
                $response['success'] = true;
                $response['message'] = 'Enregistrement réussi';
            } else {
                throw new \PDOException('Erreur lors de l\'enregistrement');
            }
        } catch (\PDOException $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
        }

        return $response;
    }

    public function deleteCategoryFromDatabase($id)
    {
        try {
            $sqlRequest = "DELETE FROM `categorie` WHERE id = :id";

            // Préparation de la requête
            $connexion = $this->database->dbConnect();
            $statement = $connexion->prepare($sqlRequest);

            $statement->bindParam(':id', $id);
            $statement->execute();
            if ($statement->rowCount() > 0) {
                $response['success'] = true;
                $response['message'] = 'Suppression réussi';
            } else {
                throw new \PDOException('Erreur lors de la suppresion');
            }
        } catch (\PDOException $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
        }
        return $response;
    }
}
