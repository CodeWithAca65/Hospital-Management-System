<?php 
    // Povezivanje sa bazom podataka koristeći PDO
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "klinika";

    try {
        $db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // echo "Uspesna konekcija sa bazom";
    } catch (PDOException $e) {
        echo "Greska pri povezivanju sa bazom: " . $e->getMessage();
        exit;
    }
?>