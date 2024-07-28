<?php

    namespace App\Controllers;

    use App\Models\NalogModel;

    class AuthController
    {
        private $conn;
        private $twig;

        public function __construct($conn, $twig)
        {
            $this->conn = $conn;
            $this->twig = $twig;
        }

        public function login()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $korisnickoIme = $_POST['korisnickoIme'];
                $sifra = $_POST['sifra'];

                $nalogModel = new NalogModel($this->conn);
                $uloga = $nalogModel->proveriNalog($korisnickoIme, $sifra);

                if ($uloga) {
                    $_SESSION['uloga'] = $uloga;
                    $_SESSION['ulogovaniKorisnik'] = $korisnickoIme;
                    echo "Ulogovani ste";
                    $this->logovanjeLogFile($uloga);
                    header('Location: index.php');
                    exit();
                } else {
                    echo "<p style='text-align:center;'>Pogrešno korisničko ime ili lozinka.</p>";
                }
            }
        }

        public function logout()
        {
            session_start();
            session_unset();
            session_destroy();

            header('Location: index.php');
            exit();
        }

        public function logovanjeLogFile($uloga) {
            
            date_default_timezone_set("Europe/Belgrade");
            $timestamp = date("Y-m-d H:i:s");

            $logText = "$timestamp - Ulogovao se: $uloga\n";

            $putanja = 'C:\wamp64\www\Klinika\log file\log_file.txt';

            file_put_contents($putanja, $logText, FILE_APPEND);
        }

    }

?>