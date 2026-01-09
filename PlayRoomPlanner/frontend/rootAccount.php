<?php

session_start();
require_once("../backend/auth_check.php");

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

?>

<?php
if(!isset($_SESSION) || $_SESSION['logged_in'] == false || $_SESSION['responsabile'] == false) {
    http_response_code(403);
    ?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>403 Forbidden</title>
    </head>
    <body>
        <h2>Errore 403: Forbidden</h2>
        <p>Non hai i permessi necessari per visualizzare la pagina.</p>
        <a href="../index.php">Torna alla home</a>
    </body>
<?php
} else {
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/fontawesome.css">
    <link rel="stylesheet" href="../css/templatemo-574-mexant.css">
    <link rel="stylesheet" href="../css/owl.css">
    <link rel="stylesheet" href="../css/animate.css">
    <link rel="stylesheet" href="../css/custom_style_carlo.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css">
    <script src="../js/gestionePrenotazioni.js"></script>
    <title>Account admin</title>
</head>

<body>
    <?php include "../common/navbar.php"; ?>


    <div class="page-heading chi-siamo-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="header-text">
                        <h2>Account amministratore</h2>
                        <div class="div-dec"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="options bar" style="margin-top: 50px; margin-bottom: 50px;">
            <div class="row mb-3">
                <div class="col-sm-3">
                    <a href="#" id="nav-resp">Responsabili corsi</a>
                </div>
                <div class="col-sm-3">
                    <a href="#" id="nav-user">Dati utenti</a>
                </div>
                <div class="col-sm-3">
                    <a href="#" id="nav-enrolls">Iscrizioni</a>
                </div>
                <div class="col-sm-3">
                    <a href="#" id="nav-bookings">Prenotazioni</a>
                </div>
            </div>
        </div>
        <div class="container" id="admin-page-content">
        </div>
    </div>

</body>
<?php } ?>