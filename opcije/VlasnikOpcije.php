<?php 
    session_start();

    if (!isset($_SESSION['uloga']) || $_SESSION['uloga'] !== 'vlasnik') {
        // Sesija nije aktivna ili korisnik nije ulogovan kao vlasnik
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
        <h1>Stranica za vlasnika klinike</h1>

        <!-- vlasnik klinike ima sledece mogucnosti --> 
        
            <?php echo "<div class='centrirano'><p>Ulogovani korisnik: $ulogovaniKorisnik</p></div>"?>
            <form action="../views/vlasnik/dodaj_doktora.twig" method="post">
                <input type="submit" value="Dodaj doktora">
            </form>
            <br>
            <form action="../views/vlasnik/dodaj_medicinsku_sestru.twig" method="post">
                <input type="submit" value="Dodaj medicinsku sestru">
            </form>
            <br>
            <form action="../views/vlasnik/izmeni_lozinku.twig" method="post">
                <input type="submit" value="Promeni lozinku">
            </form>
            <br>
        
        <form action="../index.php?naredba=logout" method="post">
            <input type="submit" value="Odjavi me">
        </form>
    </div>

</body>
</html>