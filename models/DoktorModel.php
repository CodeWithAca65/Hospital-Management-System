<?php
    namespace App\Models;
    
    class DoktorModel {
        private $conn;
    
        public function __construct($db) {
            $this->conn = $db;
        }
    
        public function dodajDoktora($korisnicko_ime, $ime, $prezime, $specijalnost) {
            $query = "INSERT INTO Doktori (korisnicko_ime, ime, prezime, specijalnost) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ssss", $korisnicko_ime, $ime, $prezime, $specijalnost);
    
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }
    
        public function dohvatiSveDoktore() {
            $query = "SELECT * FROM doktori";
            $result = $this->conn->query($query);
    
            $doktori = [];
    
            if ($result) {
                while ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
                    $doktori[] = $row;
                }
            }
    
            return $doktori;
        }

        // Metoda za dohvatanje informacija o gostu na osnovu njegovog ID-a
        public function dohvatiDoktora($doktorId) {
            $query = "SELECT * FROM doktori WHERE doktor_id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $doktorId, \PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            if (count($result) > 0) {
                return $result[0];
            }

            return null;
        }

        public function dohvatiSmeneDoktora() {
            $query = "SELECT * FROM rasporedi";
            $result = $this->conn->query($query);
    
            $smene = [];
    
            if ($result) {
                while ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
                    $smene[] = $row;
                }
            }
    
            return $smene;
        }

        // metod za raspored_rada.twig
        public function dohvatiTabeleRasporediIDoktori() {
            $query = "SELECT CONCAT(doktori.ime, ' ', doktori.prezime) AS 'doktor', doktori.specijalnost, rasporedi.doktor_id, rasporedi.smena 
                      FROM rasporedi
                      INNER JOIN doktori ON doktori.doktor_id = rasporedi.doktor_id;";
            $result = $this->conn->query($query);
    
            $smene = [];
    
            if ($result) {
                while ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
                    $smene[] = $row;
                }
            }
    
            return $smene;
        }

        public function dohvatiSmenuDoktora($doktorId) {
            $query = "SELECT * FROM rasporedi WHERE doktor_id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $doktorId, \PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            if (count($result) > 0) {
                return $result[0];
            }

            return null;
        }

        public function dohvatiNepregledanePreglede($doktor) {
            $status = 'nije_pregledan';
            $query = "SELECT * FROM pregledi WHERE status = ? AND doktor_id = ?;";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $status, \PDO::PARAM_STR);
            $stmt->bindParam(2, $doktor, \PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        }

        public function dohvatiIdDoktora($korisnickoIme) {
            $query = "SELECT doktor_id FROM doktori WHERE korisnicko_ime = :korisnicko_ime";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':korisnicko_ime', $korisnickoIme, \PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
    
            if ($result) {
                return $result['doktor_id'];
            }
    
            return null;
        }

        public function dohvatiImeISpecijalnost($doktorID) {
            $query = "SELECT CONCAT(doktori.ime, ' ', doktori.prezime, ' - ', doktori.specijalnost) AS 'doktor' FROM doktori WHERE doktor_id = :doktorID";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':doktorID', $doktorID, \PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
    
            if ($result) {
                return $result['doktor'];
            }
    
            return null;
        }

        public function izvrsiPregled($pregledId, $izvestaj) {
            $query = "UPDATE pregledi SET status = 'pregledan', informacije_o_pregledu = :izvestaj WHERE pregled_id = :pregledId";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':izvestaj', $izvestaj, \PDO::PARAM_STR);
            $stmt->bindParam(':pregledId', $pregledId, \PDO::PARAM_INT);
    
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }

        public function dohvatiSvePreglede($korisnickoIme) {
            $query = "SELECT CONCAT(pacijenti.ime, ' ', pacijenti.prezime) AS 'Pacijent', pregledi.datum, pregledi.vreme, pregledi.status, pregledi.informacije_o_pregledu 
            FROM pregledi
            INNER JOIN doktori ON doktori.doktor_id = pregledi.doktor_id
            INNER JOIN pacijenti ON pacijenti.pacijent_id = pregledi.pacijent_id
            WHERE doktori.korisnicko_ime = ?
            ORDER BY pregledi.status DESC, pregledi.datum, pregledi.vreme, pacijenti.pacijent_id;";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(1, $korisnickoIme, \PDO::PARAM_STR);
            $stmt->execute();
    
            $pregledi = [];
    
            $pregledi = [];

            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $pregledi[] = $row;
            }

            return $pregledi;
        }
        
    }
    
?>