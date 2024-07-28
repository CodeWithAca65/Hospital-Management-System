<?php
namespace App\Models;

class MedicinskaSestraModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function dodajMedicinskuSestru($ime, $prezime, $kontakt) {
        $query = "INSERT INTO MedicinskeSestre (ime, prezime, kontakt) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sss", $ime, $prezime, $kontakt);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function proveriZauzetostTermina($doktorId, $datum, $vreme) {
        $query = "SELECT COUNT(*) AS broj_pregleda FROM pregledi WHERE doktor_id = :doktorId AND datum = :datum AND vreme = :vreme";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':doktorId', $doktorId, \PDO::PARAM_INT);
        $stmt->bindValue(':datum', $datum, \PDO::PARAM_STR);
        $stmt->bindValue(':vreme', $vreme, \PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            return $result['broj_pregleda'] > 0;
        }

        public function zakaziPregled($pacijentId, $doktorId, $datum, $vreme) {

            if ($this->proveriZauzetostTermina($doktorId, $datum, $vreme)) {
                return false;
            }

            $query = "INSERT INTO pregledi (pacijent_id, doktor_id, datum, vreme, status) VALUES (:pacijentId, :doktorId, :datum, :vreme, :status)";
            $stmt = $this->conn->prepare($query);
        
            $stmt->bindValue(':pacijentId', $pacijentId, \PDO::PARAM_INT);
            $stmt->bindValue(':doktorId', $doktorId, \PDO::PARAM_INT);
            $stmt->bindValue(':datum', $datum, \PDO::PARAM_STR);
            $stmt->bindValue(':vreme', $vreme, \PDO::PARAM_STR);

            $status = 'nije_pregledan';
            $stmt->bindValue(':status', $status, \PDO::PARAM_STR);
        
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }    

        public function kreirajRaspored($raspored)
        {
            $queryCheck = "SELECT COUNT(*) FROM rasporedi WHERE doktor_id = :doktor_id";
            $queryUpdate = "UPDATE rasporedi SET smena = :smena WHERE doktor_id = :doktor_id;";
            $queryInsert = "INSERT INTO rasporedi (doktor_id, smena) VALUES (:doktor_id, :smena);";

            $stmtCheck = $this->conn->prepare($queryCheck);
            $stmtUpdate = $this->conn->prepare($queryUpdate);
            $stmtInsert = $this->conn->prepare($queryInsert);

            foreach ($raspored as $doktorId => $smene) {
                foreach ($smene as $smena => $value) {
                    if ($value) {
                        $stmtCheck->bindValue(':doktor_id', $doktorId, \PDO::PARAM_INT);
                        $stmtCheck->execute();

                        $exists = $stmtCheck->fetchColumn();

                        if ($exists > 0) {
                            $stmtUpdate->bindValue(':doktor_id', $doktorId, \PDO::PARAM_INT);
                            $stmtUpdate->bindValue(':smena', $smena, \PDO::PARAM_STR);
                            $stmtUpdate->execute();
                        } else {
                            $stmtInsert->bindValue(':doktor_id', $doktorId, \PDO::PARAM_INT);
                            $stmtInsert->bindValue(':smena', $smena, \PDO::PARAM_STR);
                            $stmtInsert->execute();
                        }
                    }
                }
            }
        }
    }
?>
