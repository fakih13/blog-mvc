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
    /**
     * @param array $postData
     * @return boolean 
     */
    public function addAtagInSql($postData)
    {
        try {
            // ajout de nouveau(x) tag(s)
            $savingTagsInSql = "INSERT INTO `tag`(`title`, `metaTitle`, `slug`, `content`) VALUES (:title, :metaTitle, :slug, :content)";
            $connexion = $this->database->dbConnect();
            $statement = $connexion->prepare($savingTagsInSql);


            $statement->bindParam(':title', $postData['title']);
            $statement->bindParam(':metaTitle', $postData['metaTitle']);
            $statement->bindParam(':slug', $postData['slug']);
            $statement->bindParam(':content', $postData['content']);

            $statement->execute();

            if ($statement->rowCount() > 0) {
                $response['success'] = true;
                $response['message'] = 'Enregistrement réussi';
            } else {
                throw new \Exception('Erreur lors de l\'enregistrement');
            }
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();
        }
    }

    public function saveACategoryInTheDatabase($postData)
    {
        $sqlRequest = "INSERT INTO `category`(`id`, `parentId`, `title`, `metaTitle`, `slug`, `content`) VALUES (:id, :parentId, :title, :metaTitle, :slug, :content)";

        // Préparation de la requête
        $connexion = $this->database->dbConnect();
        $statement = $connexion->prepare($sqlRequest);

        // Définir les valeurs pour chaque placeholder
        $statement->bindParam(':parentId', $postData['parentId']);
        $statement->bindParam(':title', $postData['title']);
        $statement->bindParam(':metaTitle', $postData['metaTitle']);
        $statement->bindParam(':slug', $postData['slug']);
        $statement->bindParam(':content', $postData['content']);

        $statement->execute();
    }
    /**
     * @param int $id
     * @-return boolean
     */
    public function deleteCategoryFromDatabase($id)
    {
        $sqlRequest = "DELETE FROM `category` WHERE id = :id";

        // Préparation de la requête
        $connexion = $this->database->dbConnect();
        $statement = $connexion->prepare($sqlRequest);

        // Définir les valeurs pour chaque placeholder
        $statement->bindParam(':parentId', $id);
    }
}
