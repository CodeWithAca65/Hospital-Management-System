<?php 
    session_start();

    if (!isset($_SESSION['uloga']) || $_SESSION['uloga'] !== 'medicinska_sestra') {
        // Sesija nije aktivna ili korisnik nije ulogovan kao medicinska sestra
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
        <h1>Stranica za medicinsku sestru</h1>

        <!-- medicinska sestra ima sledece mogucnosti --> 
        
            <?php echo "<div class='centrirano'><p>Ulogovani korisnik: $ulogovaniKorisnik</p></div>"?>

            <form action="../rute.php" method="post">
                <input type="submit" name="zakazi_pregled" value="Zakazi pregled">
            </form>
            <br>
            <form action="../rute.php" method="post">
                <input type="submit" name="raspored_rada_po_smenama" value="Raspored rada po smenama">
            </form>
            <br>
        
        <form action="../index.php?naredba=logout" method="post">
            <input type="submit" value="Odjavi me">
        </form>
    </div>

</body>
</html>