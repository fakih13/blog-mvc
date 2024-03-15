<?php

namespace App\Model;

use App\Lib\Database;

/**
 * Class manages everything related to site administrators  
 */


class AdminModel
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
     * @param string $email
     * @return array  $stmt->fetchColumn()
     */
    private function isEmailExists($email)
    {
        $sql = "SELECT COUNT(*) FROM admin WHERE email = :email";
        $connexion = $this->database->dbConnect();
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    /**
     * @param array $postData
     * @return boolean 
     */
    public function createAdmin($postData)
    {
        $response = ['success' => false, 'message' => ''];
        try {
            $email = $postData['email'];

            if ($this->isEmailExists($email)) {
                throw new \Exception("An account with this email already exists.");
            }
            $sql = "INSERT INTO `admin`(`firstName`, `lastName`, `mobile`, `email`, `passwordHash`, `registeredAt`, `lastLogin`) VALUES (:firstName, :lastName, :mobile, :email, :passwordHash, :registeredAt, :lastLogin)";
            $connexion = $this->database->dbConnect();
            $statement = $connexion->prepare($sql);

            $firstName = $postData['firstName'];
            $lastName = $postData['lastName'];
            $mobile = $postData['phone'];
            $email = $postData['email'];
            $password = password_hash($postData['password'], PASSWORD_DEFAULT);
            $registeredAt = date('Y-m-d H:i:s');
            $lastLogin = date('Y-m-d H:i:s');

            $statement->bindParam(':firstName', $firstName);
            $statement->bindParam(':lastName', $lastName);
            $statement->bindParam(':mobile', $mobile);
            $statement->bindParam(':email', $email);
            $statement->bindParam(':passwordHash', $password);
            $statement->bindParam(':registeredAt', $registeredAt);
            $statement->bindParam(':lastLogin', $lastLogin);

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

        return $response;
    }

    /**
     * @param array data
     */
    public function adminLogin($postData)
    {
        $response = ['success' => false, 'message' => ''];

        try {
            // Requête SQL pour récupérer les informations de l'administrateur
            $sql = "SELECT `id`,`firstName`, `lastName`, `mobile`, `email`, `passwordHash`, `registeredAt`, `lastLogin` FROM `admin` WHERE email = :email";
            $connexion = $this->database->dbConnect();
            $statement = $connexion->prepare($sql);

            // Paramètres de liaison avec les valeurs du formulaire
            $email = $postData['email'];
            $password = $postData['password'];

            $statement->bindParam(':email', $email);

            $statement->execute();

            // Récupérer les informations de l'administrateur
            $admin = $statement->fetch(\PDO::FETCH_ASSOC);

            // Vérifier si l'administrateur a été trouvé
            if ($admin && password_verify($password, $admin['passwordHash'])) {
                $response['success'] = true;
                $response['id'] = $admin['id'];
                $response['firstName'] = $admin['firstName'];
                $response['lastName'] = $admin['lastName'];
                $response['email'] = $admin['email'];

                // Mettre à jour la colonne lastLogin
                $currentDate = date('Y-m-d H:i:s');
                $updateSql = "UPDATE `admin` SET `lastLogin` = :lastLogin WHERE email = :email";
                $updateStatement = $connexion->prepare($updateSql);
                $updateStatement->bindParam(':lastLogin', $currentDate);
                $updateStatement->bindParam(':email', $email);
                $updateStatement->execute();
            } else {
                throw new \Exception('Identifiants incorrects ' . $admin['passwordHash']);
            }
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();
        }

        return $response;
    }

    
}
