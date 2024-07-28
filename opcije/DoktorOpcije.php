<?php 
    session_start();

    if (!isset($_SESSION['uloga']) || $_SESSION['uloga'] !== 'doktor') {
        // Sesija nije aktivna ili korisnik nije ulogovan kao doktor
        header('Location: ../index.php');
        exit();
    }

    $ulogovaniKorisnik = $_SESSION['ulogovaniKorisnik'];
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="centrirano">
        <h1>Stranica za doktore</h1>

        <!-- doktor klinike ima sledece mogucnosti --> 
        
            <?php echo "<div class='centrirano'><p>Ulogovani korisnik: $ulogovaniKorisnik</p></div>"?>

            <form action="../rute.php" method="post">
                <input type="submit" name="pregled_nije_pregledan" value="Prikazi sve preglede koje trebam obaviti">
            </form>
            <br>
            <form action="../rute.php" method="post">
                <input type="submit" name="prikazi_sve_preglede" value="Prikazi sve preglede">
            </form>
            <br>
        
        
        <form action="../index.php?naredba=logout" method="post">
            <input type="submit" value="Odjavi me">
        </form>
    </div>
</body>
</html>