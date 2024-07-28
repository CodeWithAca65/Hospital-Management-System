<?php
    namespace App\Models;
    
    class VlasnikKlinikeModel {
        private $conn;
    
        public function __construct($db) {
            $this->conn = $db;
        }
    
        public function promeniLozinkuVlasnika($lozinka) {
            $query = "UPDATE nalozi SET sifra = ? WHERE uloga = 'vlasnik';";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(1, $lozinka, \PDO::PARAM_STR);
    
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }
    
        public function dodajDoktora($ime, $prezime, $specijalnost, $korisnickoIme, $lozika) {
            $query = "INSERT INTO doktori (korisnicko_ime, ime, prezime, specijalnost) VALUES (?, ?, ?, ?);
                      INSERT INTO nalozi (uloga, korisnicko_ime, sifra) VALUES (?, ?, ?);";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(1, $korisnickoIme, \PDO::PARAM_STR);
            $stmt->bindValue(2, $ime, \PDO::PARAM_STR);
            $stmt->bindValue(3, $prezime, \PDO::PARAM_STR);
            $stmt->bindValue(4, $specijalnost, \PDO::PARAM_STR);
            $uloga = "doktor";
            $stmt->bindValue(5, $uloga, \PDO::PARAM_STR);
            $stmt->bindValue(6, $korisnickoIme, \PDO::PARAM_STR);
            $stmt->bindValue(7, $lozika, \PDO::PARAM_STR);
    
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }
    
        public function dodajMedicinskuSestru($ime, $prezime, $kontakt, $korisnickoIme, $lozika) {
            $query = "INSERT INTO medicinskesestre (ime, prezime, kontakt) VALUES (?, ?, ?);
                      INSERT INTO nalozi (uloga, korisnicko_ime, sifra) VALUES (?, ?, ?);";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(1, $ime, \PDO::PARAM_STR);
            $stmt->bindValue(2, $prezime, \PDO::PARAM_STR);
            $stmt->bindValue(3, $kontakt, \PDO::PARAM_STR);
            $uloga = "medicinska_sestra";
            $stmt->bindValue(4, $uloga, \PDO::PARAM_STR);
            $stmt->bindValue(5, $korisnickoIme, \PDO::PARAM_STR);
            $stmt->bindValue(6, $lozika, \PDO::PARAM_STR);
    
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }
    }
?>