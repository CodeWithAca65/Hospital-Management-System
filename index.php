<?php

    require 'DatabaseConnection.php';
    require 'vendor/autoload.php';

    $loader = new \Twig\Loader\FilesystemLoader('views');
    $twig = new \Twig\Environment($loader);

    session_start();

    // LOGIN i LOGOUT 

    if (isset($_SESSION['uloga'])) {
        // Ako je korisnik vec ulogovan onda prikazujem odgovarajuci sadrzaj u zavisnosti od uloge
        if ($_SESSION['uloga'] === 'vlasnik') {
            header('Location: opcije/VlasnikOpcije.php');
        } elseif ($_SESSION['uloga'] === 'medicinska_sestra') {
            header('Location: opcije/MedicinskaSestraOpcije.php');
        } else {
            header('Location: opcije/DoktorOpcije.php');
        }
    } else {
        // Ako korisnik nije ulogovan prikazujem login formu
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_GET['naredba']) && $_GET['naredba'] === 'login') {
                $authController = new \App\Controllers\AuthController($db, $twig);
                $authController->login();
                exit();
            }
        }

        echo $twig->render('logovanje/login.twig');
    }

    // Ruta za logout
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_GET['naredba']) && $_GET['naredba'] === 'logout') {
            $authController = new \App\Controllers\AuthController($conn, $twig);
            $authController->logout();
            exit();
        }
    }

?>