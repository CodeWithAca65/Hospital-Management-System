<?php

    namespace App\Controllers;

    use App\Models\VlasnikKlinikeModel;

    class VlasnikKlinikeController {
        private $model;

        public function __construct($model) {
            $this->model = $model;
        }

        public function promeniLozinku($lozinka) {
            if ($this->model->promeniLozinkuVlasnika($lozinka)) {
                header('Location: index.php?success=1');
            } else {
                header('Location: index.php?error=1');
            }
        }

        public function dodajDoktora($ime, $prezime, $specijalnost, $korisnickoIme, $lozinka) {
            if ($this->model->dodajDoktora($ime, $prezime, $specijalnost, $korisnickoIme, $lozinka)) {
                header('Location: index.php?success=1');
            } else {
                header('Location: index.php?error=1');
            }
        }

        public function dodajMedicinskuSestru($ime, $prezime, $kontakt, $korisnickoIme, $lozinka) {
            if ($this->model->dodajMedicinskuSestru($ime, $prezime, $kontakt, $korisnickoIme, $lozinka)) {
                header('Location: index.php?success=1');
            } else {
                header('Location: index.php?error=1');
            }
        }
    }
?>
