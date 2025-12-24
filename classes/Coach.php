<?php
require_once 'Utilisateur.php';

/**
 * Classe Coach
 * Hérite de la classe Utilisateur - Démontre l'héritage
 */

class Coach extends Utilisateur {
    // Propriétés privées spécifiques au coach
    private $discipline;
    private $annees_experience;
    private $description;

    /**
     * Constructeur
     * @param PDO $db
     */
    public function __construct($db) {
        parent::__construct($db);
        $this->table_name = "coachs";
    }

    // Getters
    public function getDiscipline() {
        return $this->discipline;
    }

    public function getAnneesExperience() {
        return $this->annees_experience;
    }

    public function getDescription() {
        return $this->description;
    }

    // Setters
    public function setDiscipline($discipline) {
        $this->discipline = htmlspecialchars(strip_tags($discipline));
    }

    public function setAnneesExperience($annees) {
        $this->annees_experience = intval($annees);
    }

    public function setDescription($description) {
        $this->description = htmlspecialchars(strip_tags($description));
    }

    /**
     * Créer un nouveau coach
     * @return bool
     */
    public function creer() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (nom, prenom, email, password, discipline, annees_experience, description) 
                  VALUES 
                  (:nom, :prenom, :email, :password, :discipline, :annees_experience, :description)";

        $stmt = $this->conn->prepare($query);

        // Bind des paramètres
        $stmt->bindParam(':nom', $this->nom);
        $stmt->bindParam(':prenom', $this->prenom);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':discipline', $this->discipline);
        $stmt->bindParam(':annees_experience', $this->annees_experience);
        $stmt->bindParam(':description', $this->description);

        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    /**
     * Lire tous les coachs
     * @return PDOStatement
     */
    public function lireTous() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY nom ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Lire un coach par ID
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
            $this->discipline = $row['discipline'];
            $this->annees_experience = $row['annees_experience'];
            $this->description = $row['description'];
            return true;
        }
        return false;
    }

    /**
     * Mettre à jour le profil du coach
     * @return bool
     */
    public function mettreAJour() {
        $query = "UPDATE " . $this->table_name . " 
                  SET nom = :nom, 
                      prenom = :prenom, 
                      email = :email, 
                      discipline = :discipline, 
                      annees_experience = :annees_experience, 
                      description = :description 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':nom', $this->nom);
        $stmt->bindParam(':prenom', $this->prenom);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':discipline', $this->discipline);
        $stmt->bindParam(':annees_experience', $this->annees_experience);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    /**
     * Méthode pour afficher les informations complètes du coach
     * Surcharge de la méthode parent
     * @return string
     */
    public function afficherInfo() {
        return parent::afficherInfo() . 
               " - Discipline: " . $this->discipline . 
               " - Expérience: " . $this->annees_experience . " ans";
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
