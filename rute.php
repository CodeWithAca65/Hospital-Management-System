<?php 
    require_once 'vendor/autoload.php';
    require_once 'DatabaseConnection.php';

    use App\Models\{
        VlasnikKlinikeModel,
        MedicinskaSestraModel,
        DoktorModel,
    PacijentModel
    };
    use App\Controllers\{
        VlasnikKlinikeController,
        MedicinskaSestraController,
        DoktorController,
    IzvestajController,
    PacijentController
    };
    use Twig\Loader\FilesystemLoader;
    use Twig\Environment;
    
    // Konfiguracija Twig okruzenja
    $loader = new FilesystemLoader('views');
    $twig = new Environment($loader);

    // PACIJENT
    // Obrada zahteva koje ima pacijent
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        $modelPacijenta = new PacijentModel($db);
        $pacijentController = new PacijentController($modelPacijenta, $db, $twig);

        if(isset($_POST['prikaziRaspored'])) {
            $doktorModel = new \App\Models\DoktorModel($db);
            $pacijentController->prikaziRasporedRadaDoktora($doktorModel);
            exit();
        }
    } else {
        echo "-";
    }
    
    // MEDICINSKA SESTRA
    // Obrada zahteva za opcije koje ima medicinska sestra
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        $modelMedicinskeSestre = new MedicinskaSestraModel($db);
        $medicinskaSestraController = new MedicinskaSestraController($modelMedicinskeSestre, $db, $twig);

        if(isset($_POST['raspored_rada_po_smenama'])) {
            $medicinskaSestraController->dohvatiDoktore();
            exit();
        }
        if (isset($_POST['sacuvaj_raspored'])) {
            $medicinskaSestraController->kreirajRaspored($_POST['raspored']);
            echo $twig->render('uspeh.twig');
            exit();
        }
        if (isset($_POST['zakazi_pregled'])) {
            $medicinskaSestraController->prikaziFormuZaZakazivanje();
            exit();
        }
        if (isset($_POST['potvrdi_zakazani_pregled'])) {
            $medicinskaSestraController->zakaziPregled();
            exit();
        }
        if (isset($_POST['preuzmi_podatke_u_pdf'])) {
            $izvestajController = new IzvestajController($db, $twig);
            $ime = $_POST['ime'];
            $prezime = $_POST['prezime'];
            $jmbg = $_POST['jmbg'];
            $kontakt = $_POST['kontakt'];
            $doktor_id = $_POST['doktor_id'];
            $datum = $_POST['datum'];
            $vreme = $_POST['vreme'];
            $formData = [
                'ime' => $ime,
                'prezime' => $prezime,
                'jmbg' => $jmbg,
                'kontakt' => $kontakt,
                'doktor_id' => $doktor_id,
                'datum' => $datum,
                'vreme' => $vreme
            ]; 
            
            $izvestajController->generatePdf($formData);
            exit();
        }
    } else {
        echo "-";
    }

    // DOKTOR
    // Obrada zahteva za opcije koje ima doktor
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        $modelDoktor = new DoktorModel($db);
        $doktorController = new DoktorController($modelDoktor, $db, $twig);

        session_start();
        $ulogovaniKorisnik = $_SESSION['ulogovaniKorisnik'];

        if(isset($_POST['pregled_nije_pregledan'])) {
            $idDoktora = $doktorController->dohvatiIdDoktora($ulogovaniKorisnik);
            $doktorController->dohvatiNepregledanePreglede($idDoktora);
            exit();
        }
        if (isset($_POST['izvrsi_pregled'])) {
            $pregledId = $_GET['pregled'];
            $doktorController->prikaziFormuZaPregled($pregledId);
            exit();
        }
        if (isset($_POST['sacuvaj_pregled'])) {
            $pregledId = $_GET['pregled'];
            $izvestaj = $_POST['izvestaj'];
            $doktorController->izvrsiPregledPacijenta($pregledId, $izvestaj);
            echo $twig->render('uspeh.twig');
            exit();
        }
        if (isset($_POST['prikazi_sve_preglede'])) {
            $doktorController->dohvatiSvePreglede($ulogovaniKorisnik);
            exit();
        }
        if (isset($_POST['izveziExcel'])) {
            $idDoktora = $doktorController->dohvatiIdDoktora($ulogovaniKorisnik);
            $izvestajController = new IzvestajController($db, $twig);
            $izvestajController->izveziUExcel($idDoktora);
            exit();
        }
    } else {
        echo "-";
    }

    // VLASNIK
    // Obrada zahteva za opcije koje ima vlasnik klinike
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        $modelVlasnik = new VlasnikKlinikeModel($db);
        $controllerVlasnik = new VlasnikKlinikeController($modelVlasnik);

        if (isset($_POST['promeni_lozinku'])) {
            if ($controllerVlasnik->promeniLozinku($_POST['lozinka'])) {
                echo $twig->render('uspeh.twig');
            } else {
                echo $twig->render('greska.twig');
            }
            exit();
        }
        if (isset($_POST['dodaj_doktora'])) {
            if ($controllerVlasnik->dodajDoktora($_POST['ime'], $_POST['prezime'], $_POST['specijalnost'], $_POST['korisnickoIme'], $_POST['lozinka'])) {
                echo $twig->render('uspeh.twig');
            } else {
                echo $twig->render('greska.twig');
            }
            exit();
        }
        if (isset($_POST['dodaj_medicinsku_sestru'])) {
            if ($controllerVlasnik->dodajMedicinskuSestru($_POST['ime'], $_POST['prezime'], $_POST['kontakt'], $_POST['korisnickoIme'], $_POST['lozinka'])) {
                echo $twig->render('uspeh.twig');
            } else {
                echo $twig->render('greska.twig');
            }
            exit();
        }
    } else {
        echo $twig->render('logovanje/login.twig');
    }
    
?>