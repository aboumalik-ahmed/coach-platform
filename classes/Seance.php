<?php
/**
 * Classe Seance
 * Gère les séances d'entraînement
 */

class Seance {
    // Propriétés privées pour l'encapsulation
    private $id;
    private $coach_id;
    private $date_seance;
    private $heure;
    private $duree;
    private $statut; // 'disponible' ou 'reservee'
    private $conn;
    private $table_name = "seances";

    /**
     * Constructeur
     * @param PDO $db
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getCoachId() {
        return $this->coach_id;
    }

    public function getDateSeance() {
        return $this->date_seance;
    }

    public function getHeure() {
        return $this->heure;
    }

    public function getDuree() {
        return $this->duree;
    }

    public function getStatut() {
        return $this->statut;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setCoachId($coach_id) {
        $this->coach_id = intval($coach_id);
    }

    public function setDateSeance($date) {
        $this->date_seance = htmlspecialchars(strip_tags($date));
    }

    public function setHeure($heure) {
        $this->heure = htmlspecialchars(strip_tags($heure));
    }

    public function setDuree($duree) {
        $this->duree = intval($duree);
    }

    public function setStatut($statut) {
        $this->statut = htmlspecialchars(strip_tags($statut));
    }

    /**
     * Créer une nouvelle séance
     * @return bool
     */
    public function creer() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (coach_id, date_seance, heure, duree, statut) 
                  VALUES 
                  (:coach_id, :date_seance, :heure, :duree, :statut)";

        $stmt = $this->conn->prepare($query);

        // Statut par défaut: disponible
        if(empty($this->statut)) {
            $this->statut = 'disponible';
        }

        $stmt->bindParam(':coach_id', $this->coach_id);
        $stmt->bindParam(':date_seance', $this->date_seance);
        $stmt->bindParam(':heure', $this->heure);
        $stmt->bindParam(':duree', $this->duree);
        $stmt->bindParam(':statut', $this->statut);

        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    /**
     * Lire toutes les séances disponibles
     * @return PDOStatement
     */
    public function lireDisponibles() {
        $query = "SELECT s.*, c.nom as coach_nom, c.prenom as coach_prenom, c.discipline 
                  FROM " . $this->table_name . " s 
                  LEFT JOIN coachs c ON s.coach_id = c.id 
                  WHERE s.statut = 'disponible' 
                  AND s.date_seance >= CURDATE()
                  ORDER BY s.date_seance ASC, s.heure ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Lire les séances d'un coach
     * @param int $coach_id
     * @return PDOStatement
     */
    public function lireParCoach($coach_id) {
        $query = "SELECT s.* 
                  FROM " . $this->table_name . " s 
                  WHERE s.coach_id = :coach_id 
                  ORDER BY s.date_seance ASC, s.heure ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':coach_id', $coach_id);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Lire une séance par ID
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
            $this->coach_id = $row['coach_id'];
            $this->date_seance = $row['date_seance'];
            $this->heure = $row['heure'];
            $this->duree = $row['duree'];
            $this->statut = $row['statut'];
            return true;
        }
        return false;
    }

    /**
     * Mettre à jour une séance
     * @return bool
     */
    public function mettreAJour() {
        $query = "UPDATE " . $this->table_name . " 
                  SET date_seance = :date_seance, 
                      heure = :heure, 
                      duree = :duree, 
                      statut = :statut 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':date_seance', $this->date_seance);
        $stmt->bindParam(':heure', $this->heure);
        $stmt->bindParam(':duree', $this->duree);
        $stmt->bindParam(':statut', $this->statut);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    /**
     * Supprimer une séance
     * @return bool
     */
    public function supprimer() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    /**
     * Méthode pour afficher les informations de la séance
     * @return string
     */
    public function afficherInfo() {
        return "Séance le " . $this->date_seance . 
               " à " . $this->heure . 
               " - Durée: " . $this->duree . " min" .
               " - Statut: " . $this->statut;
    }
}
?>
