<?php
/**
 * Classe Reservation
 * Gère les réservations de séances par les sportifs
 */

class Reservation {
    // Propriétés privées pour l'encapsulation
    private $id;
    private $seance_id;
    private $sportif_id;
    private $date_reservation;
    private $conn;
    private $table_name = "reservations";

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

    public function getSeanceId() {
        return $this->seance_id;
    }

    public function getSportifId() {
        return $this->sportif_id;
    }

    public function getDateReservation() {
        return $this->date_reservation;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setSeanceId($seance_id) {
        $this->seance_id = intval($seance_id);
    }

    public function setSportifId($sportif_id) {
        $this->sportif_id = intval($sportif_id);
    }

    public function setDateReservation($date) {
        $this->date_reservation = $date;
    }

    /**
     * Créer une nouvelle réservation
     * @return bool
     */
    public function creer() {
        // Vérifier que la séance est disponible
        $query_check = "SELECT statut FROM seances WHERE id = :seance_id";
        $stmt_check = $this->conn->prepare($query_check);
        $stmt_check->bindParam(':seance_id', $this->seance_id);
        $stmt_check->execute();
        
        $seance = $stmt_check->fetch(PDO::FETCH_ASSOC);
        if($seance['statut'] !== 'disponible') {
            return false;
        }

        // Créer la réservation
        $query = "INSERT INTO " . $this->table_name . " 
                  (seance_id, sportif_id, date_reservation) 
                  VALUES 
                  (:seance_id, :sportif_id, NOW())";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':seance_id', $this->seance_id);
        $stmt->bindParam(':sportif_id', $this->sportif_id);

        if($stmt->execute()) {
            // Mettre à jour le statut de la séance
            $query_update = "UPDATE seances SET statut = 'reservee' WHERE id = :seance_id";
            $stmt_update = $this->conn->prepare($query_update);
            $stmt_update->bindParam(':seance_id', $this->seance_id);
            $stmt_update->execute();

            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    /**
     * Lire les réservations d'un sportif
     * @param int $sportif_id
     * @return PDOStatement
     */
    public function lireParSportif($sportif_id) {
        $query = "SELECT r.*, s.date_seance, s.heure, s.duree, 
                         c.nom as coach_nom, c.prenom as coach_prenom, c.discipline 
                  FROM " . $this->table_name . " r 
                  LEFT JOIN seances s ON r.seance_id = s.id 
                  LEFT JOIN coachs c ON s.coach_id = c.id 
                  WHERE r.sportif_id = :sportif_id 
                  ORDER BY s.date_seance DESC, s.heure DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':sportif_id', $sportif_id);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Lire les réservations pour un coach
     * @param int $coach_id
     * @return PDOStatement
     */
    public function lireParCoach($coach_id) {
        $query = "SELECT r.*, s.date_seance, s.heure, s.duree, 
                         sp.nom as sportif_nom, sp.prenom as sportif_prenom 
                  FROM " . $this->table_name . " r 
                  LEFT JOIN seances s ON r.seance_id = s.id 
                  LEFT JOIN sportifs sp ON r.sportif_id = sp.id 
                  WHERE s.coach_id = :coach_id 
                  ORDER BY s.date_seance DESC, s.heure DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':coach_id', $coach_id);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Vérifier si un sportif a déjà réservé une séance
     * @param int $seance_id
     * @param int $sportif_id
     * @return bool
     */
    public function dejaReservee($seance_id, $sportif_id) {
        $query = "SELECT id FROM " . $this->table_name . " 
                  WHERE seance_id = :seance_id AND sportif_id = :sportif_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':seance_id', $seance_id);
        $stmt->bindParam(':sportif_id', $sportif_id);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }

    /**
     * Méthode pour afficher les informations de la réservation
     * @return string
     */
    public function afficherInfo() {
        return "Réservation #" . $this->id . 
               " - Séance: " . $this->seance_id . 
               " - Date: " . $this->date_reservation;
    }
}
?>
