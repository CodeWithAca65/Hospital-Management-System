<?php

    namespace App\Controllers;

    class MedicinskaSestraController {
        private $model;
        private $conn;
        private $twig;

        public function __construct($model, $conn, $twig) {
            $this->model = $model;
            $this->conn = $conn;
            $this->twig = $twig;
        }

        public function kreirajRaspored($raspored) {
            return $this->model->kreirajRaspored($raspored);
        }

        public function dohvatiDoktore() {
            $doktorModel = new \App\Models\DoktorModel($this->conn);
            $doktoriIDs = $doktorModel->dohvatiSveDoktore();
            
            $doktori = [];
        
            foreach ($doktoriIDs as $doktorId) {
                $doktor = $doktorModel->dohvatiDoktora($doktorId['doktor_id']);
                
                $smenaDoktora = $doktorModel->dohvatiSmenuDoktora($doktorId['doktor_id']);
                
                // Pravim asocijativni niz sa informacijama o smenama
                $smene = [];
                if ($smenaDoktora !== null) {
                    $smene[$smenaDoktora['smena']] = true;
                }
                
                $doktor['smene'] = $smene;

                $doktori[] = $doktor;
            }
        
            echo $this->twig->render('medicinska sestra/raspored_rada.twig', ['doktori' => $doktori]);
        }  
        
        public function prikaziFormuZaZakazivanje() {
            
            $pacijentModel = new \App\Models\PacijentModel($this->conn);
            $pacijenti = $pacijentModel->dohvatiSvePacijente();

            $doktorModel = new \App\Models\DoktorModel($this->conn);
            $doktori = $doktorModel->dohvatiSveDoktore();
    
            echo $this->twig->render('medicinska sestra/zakazi_pregled.twig', ['pacijenti' => $pacijenti, 'doktori' => $doktori]);
        }
    
        public function zakaziPregled() {
            if (isset($_POST['potvrdi_zakazani_pregled'])) {

                $pacijentId = $_POST['jmbg'];
                $ime = $_POST['ime'];
                $prezime = $_POST['prezime'];
                $kontakt = $_POST['kontakt'];
                $doktorId = $_POST['doktor_id'];
                $datum = $_POST['datum'];
                $vreme = $_POST['vreme'];

                $pacijentModel = new \App\Models\PacijentModel($this->conn);
                $pacijent = $pacijentModel->dohvatiPacijenta($pacijentId);

                if (!$pacijent) {
                    $pacijentModel->napraviPacijenta($pacijentId, $ime, $prezime, $kontakt);

                    if ($this->model->zakaziPregled($pacijentId, $doktorId, $datum, $vreme)) {
                        header('Location: views/uspehZakazivanje.twig');
                    } else {
                        $greska = "Termin je zauzet";
                        echo $this->twig->render('greskaZakazivanje.twig', ['greska' => $greska]);
                    }
                }
                else {
                    if ($this->model->zakaziPregled($pacijentId, $doktorId, $datum, $vreme)) {
                        header('Location: views/uspehZakazivanje.twig');
                    } else {
                        $greska = "Termin je zauzet";
                        echo $this->twig->render('greskaZakazivanje.twig', ['greska' => $greska]);
                    }
                }
            }
        }

    }
?>