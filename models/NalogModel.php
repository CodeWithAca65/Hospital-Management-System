<?php

    namespace App\Models;

    class NalogModel
    {
        private $conn;

        public function __construct($conn)
        {
            $this->conn = $conn;
        }

        public function proveriNalog($korisnickoIme, $sifra)
        {
            $query = "SELECT * FROM nalozi WHERE korisnicko_ime = :korisnicko_ime AND sifra = :sifra";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':korisnicko_ime', $korisnickoIme);
            $stmt->bindParam(':sifra', $sifra);
            $stmt->execute();
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            if ($stmt->rowCount() === 1) {
                $row = $result[0]; 
                return $row['uloga'];
            }

            return null;
        }

    }

?>