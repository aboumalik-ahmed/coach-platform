<?php
/**
 * Classe de base Utilisateur
 * Cette classe sera héritée par Coach et Sportif
 * Démontre le principe d'héritage en POO
 */

class Utilisateur {
    // Propriétés protégées pour permettre l'héritage
    protected $id;
    protected $nom;
    protected $prenom;
    protected $email;
    protected $password;
    protected $conn;
    protected $table_name;

    /**
     * Constructeur
     * @param PDO $db Connexion à la base de données
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    // Getters - Encapsulation
    public function getId() {
        return $this->id;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getPrenom() {
        return $this->prenom;
    }

    public function getEmail() {
        return $this->email;
    }

    // Setters - Encapsulation
    public function setId($id) {
        $this->id = $id;
    }

    public function setNom($nom) {
        $this->nom = htmlspecialchars(strip_tags($nom));
    }

    public function setPrenom($prenom) {
        $this->prenom = htmlspecialchars(strip_tags($prenom));
    }

    public function setEmail($email) {
        $this->email = htmlspecialchars(strip_tags($email));
    }

    public function setPassword($password) {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Méthode pour obtenir le nom complet
     * @return string
     */
    public function getNomComplet() {
        return $this->prenom . " " . $this->nom;
    }

    /**
     * Méthode de connexion
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function login($email, $password) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if(password_verify($password, $row['password'])) {
                $this->id = $row['id'];
                $this->nom = $row['nom'];
                $this->prenom = $row['prenom'];
                $this->email = $row['email'];
                return true;
            }
        }
        return false;
    }

    /**
     * Méthode pour afficher les informations de base
     * @return string
     */
    public function afficherInfo() {
        return "Nom: " . $this->getNomComplet() . " - Email: " . $this->email;
    }
}
?>
