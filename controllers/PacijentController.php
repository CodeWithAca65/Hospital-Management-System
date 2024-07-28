<?php

    namespace App\Controllers;

    class PacijentController {
        private $model;
        private $conn;
        private $twig;

        public function __construct($model, $conn, $twig) {
            $this->model = $model;
            $this->conn = $conn;
            $this->twig = $twig;
        }

        public function prikaziRasporedRadaDoktora($doktorModel)
        {
            // Ovde dohvatamo raspored rada doktora 
            $rasporediDoktora = $doktorModel->dohvatiTabeleRasporediIDoktori();

            $doktori = [];

            // Prolazim kroz rasporede doktora i kreiram niz doktora sa rasporedom
            foreach ($rasporediDoktora as $raspored) {
                $doktorId = $raspored['doktor_id'];
                $smena = $raspored['smena'];
                $doktor = $raspored['doktor'];
                $doktorSpecijalnost = $raspored['specijalnost'];

                $doktori[$doktorId]['doktor'] = $doktor;
                $doktori[$doktorId]['specijalnost'] = $doktorSpecijalnost;

                // Kreiram asocijativni niz za svakog doktora sa rasporedom
                if (!isset($doktori[$doktorId])) {
                    $doktori[$doktorId] = [
                        'raspored' => [
                            'prva' => false,
                            'druga' => false,
                            'treca' => false,
                        ],
                    ];
                }

                // Postavljam status radne smene na true
                if ($smena === 'prva') {
                    $doktori[$doktorId]['raspored']['prva'] = true;
                } elseif ($smena === 'druga') {
                    $doktori[$doktorId]['raspored']['druga'] = true;
                } elseif ($smena === 'treca') {
                    $doktori[$doktorId]['raspored']['treca'] = true;
                }
            }
            
            // Renderujem Twig template sa podacima
            echo $this->twig->render('pacijent/raspored_rada.twig', ['doktori' => $doktori]);
        }

    }

?>