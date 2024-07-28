<?php

    namespace App\Controllers;

    class DoktorController {
        private $model;
        private $conn;
        private $twig;

        public function __construct($model, $conn, $twig) {
            $this->model = $model;
            $this->conn = $conn;
            $this->twig = $twig;
        }

        public function dohvatiNepregledanePreglede($doktor) {
            $pregledi = $this->model->dohvatiNepregledanePreglede($doktor);

            $modelPacijent = new \App\Models\PacijentModel($this->conn);
            
            foreach ($pregledi as $pregled) {
                $imePrezimePacijenta = $modelPacijent->dohvatiImePrezimePacijenta($pregled['pacijent_id']);
                $imePrezimePacijenata[] = $imePrezimePacijenta['Pacijent'];
            }
            
            if (empty($pregledi)) {
                echo $this->twig->render('doktor/nepregledani_pregledi.twig');
            } else {
                echo $this->twig->render('doktor/nepregledani_pregledi.twig', ['pregledi' => $pregledi, 'imePrezimePacijenata' => $imePrezimePacijenata]);
            }
        }

        public function dohvatiIdDoktora($korisnickoIme) {
            return $this->model->dohvatiIdDoktora($korisnickoIme);
        }

        public function dohvatiImeISpecijalnost($doktorID) {
            return $this->model->dohvatiImeISpecijalnost($doktorID);
        }

        public function dohvatiSvePreglede($korisnickoIme) {
            $pregledi = $this->model->dohvatiSvePreglede($korisnickoIme);
            echo $this->twig->render('doktor/svi_pregledi.twig', ['pregledi' => $pregledi]);
        }

        public function prikaziFormuZaPregled($pregledId) {
            echo $this->twig->render('doktor/pregled.twig', ['pregledId' => $pregledId]);
        }

        public function izvrsiPregledPacijenta($pregledId, $izvestaj) {
            return $this->model->izvrsiPregled($pregledId, $izvestaj);
        }

    }
?>