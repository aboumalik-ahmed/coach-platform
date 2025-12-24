<?php
require_once 'Utilisateur.php';

/**
 * Classe Sportif
 * Hérite de la classe Utilisateur - Démontre l'héritage
 */

class Sportif extends Utilisateur {
    /**
     * Constructeur
     * @param PDO $db
     */
    public function __construct($db) {
        parent::__construct($db);
        $this->table_name = "sportifs";
    }

    /**
     * Créer un nouveau sportif
     * @return bool
     */
    public function creer() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (nom, prenom, email, password) 
                  VALUES 
                  (:nom, :prenom, :email, :password)";

        $stmt = $this->conn->prepare($query);

        // Bind des paramètres
        $stmt->bindParam(':nom', $this->nom);
        $stmt->bindParam(':prenom', $this->prenom);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);

        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    /**
     * Lire un sportif par ID
     * @param int $id
     * @return bool
     */
    public function lireUn($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->nom = $row['nom'];
            $this->prenom = $row['prenom'];
            $this->email = $row['email'];
            return true;
        }
        return false;
    }

    /**
     * Vérifier si l'email existe déjà
     * @param string $email
     * @return bool
     */
    public function emailExiste($email) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
?>
