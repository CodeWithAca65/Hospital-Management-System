<?php
    namespace App\Models;

    class PacijentModel {
        private $conn;

        public function __construct($db) {
            $this->conn = $db;
        }

        public function napraviPacijenta($jmbg ,$ime, $prezime, $kontakt) {
            $query = "INSERT INTO pacijenti (pacijent_id, ime, prezime, kontakt) VALUES (:pacijent_id, :ime, :prezime, :kontakt)";
            $stmt = $this->conn->prepare($query);
    
            $stmt->bindValue(':pacijent_id', $jmbg, \PDO::PARAM_STR);
            $stmt->bindValue(':ime', $ime, \PDO::PARAM_STR);
            $stmt->bindValue(':prezime', $prezime, \PDO::PARAM_STR);
            $stmt->bindValue(':kontakt', $kontakt, \PDO::PARAM_STR);
    
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }

        public function dohvatiSvePacijente() {
            $query = "SELECT * FROM pacijenti";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $pacijenti = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    
            return $pacijenti;
        }

        public function dohvatiPacijenta($pacijentId) {
            $query = "SELECT * FROM pacijenti WHERE pacijent_id = :pacijentId";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':pacijentId', $pacijentId, \PDO::PARAM_INT);
            $stmt->execute();
            $pacijent = $stmt->fetch(\PDO::FETCH_ASSOC);
    
            return $pacijent;
        }

        public function dohvatiImePrezimePacijenta($pacijentId) {
            $query = "SELECT CONCAT(pacijenti.ime, ' ', pacijenti.prezime) AS 'Pacijent' FROM pacijenti WHERE pacijent_id = :pacijentId";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':pacijentId', $pacijentId, \PDO::PARAM_INT);
            $stmt->execute();
            $pacijent = $stmt->fetch(\PDO::FETCH_ASSOC);
    
            return $pacijent;
        }
    }
?>