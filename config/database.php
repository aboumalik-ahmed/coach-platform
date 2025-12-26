<?php

class Database {
    // Propriétés privées pour l'encapsulation
    private $host = "localhost";
    private $db_name = "sportify_db";
    private $username = "ahmed";
    private $password = "password";
    private $conn;

    /**
     * Méthode pour établir la connexion à la base de données
     * @return PDO | null
     */
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Erreur de connexion : " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>
