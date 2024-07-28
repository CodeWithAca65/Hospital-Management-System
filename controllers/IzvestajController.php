<?php

    namespace App\Controllers;

    use App\Controllers\DoktorController;
    use App\Models\DoktorModel;
    use Dompdf\Dompdf;

    class IzvestajController
    {
        private $conn;
        private $twig;

        public function __construct($conn, $twig)
        {
            $this->conn = $conn;
            $this->twig = $twig;
        }

        function izveziUExcel($doktor)
        {
            $status = 'pregledan';
            $query = "SELECT pacijenti.pacijent_id, CONCAT(pacijenti.ime, ' ', pacijenti.prezime) AS 'Pacijent', pacijenti.kontakt, CONCAT(pregledi.datum, ' ', pregledi.vreme) AS 'Datum i vreme', pregledi.informacije_o_pregledu 
                      FROM pregledi
                      INNER JOIN pacijenti ON pacijenti.pacijent_id = pregledi.pacijent_id
                      WHERE pregledi.status = ? AND pregledi.doktor_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $status, \PDO::PARAM_STR);
            $stmt->bindParam(2, $doktor, \PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            // preuzeti fajl je u Downloads
            $file = 'Izvestaj svih pregleda.csv';
            $fp = fopen($file, 'w');

            // Dodajem zaglavlja
            fputcsv($fp, array('JMBG', 'Pacijent', 'Kontakt pacijenta', 'Datum i vreme', 'Informacije o pregledu'));

            // Dodajem podatke u fajl
            foreach ($result as $row) {
                fputcsv($fp, $row);
            }

            fclose($fp);

            // Podesavanja
            header('Content-Type: text/csv');   
            header('Content-Disposition: attachment; filename="' . $file . '"');  
            header('Cache-Control: max-age=0');  

            readfile($file);

            unlink($file);

            exit();
        }

        public function generatePdf($formData)
        {
            $dompdf = new Dompdf();
            $dompdf->setPaper('A4', 'portrait');

            $doktorID = $formData['doktor_id'];

            $modelDoktor = new DoktorModel($this->conn);
            $doktorController = new DoktorController($modelDoktor, $this->conn, $this->twig);
            $doktor = $doktorController->dohvatiImeISpecijalnost($doktorID);

            $html = "
                    <h1>Podaci o zakazanom pregledu</h1>
                    <p>Ime: " . $formData['ime'] . "</p>
                    <p>Prezime: " . $formData['prezime'] . "</p>
                    <p>JMBG: " . $formData['jmbg'] . "</p>
                    <p>Kontakt: " . $formData['kontakt'] . "</p>
                    <p>Doktor: " . $doktor . "</p>
                    <p>Datum: " . $formData['datum'] . "</p>
                    <p>Vreme: " . $formData['vreme'] . "</p>
            ";
            //echo $html;

            $dompdf->loadHtml($html);

            $dompdf->render();

            $imeFajla = 'Izvestaj zakazanog pregleda.pdf';
            $dompdf->stream($imeFajla);
        }
    }

?>