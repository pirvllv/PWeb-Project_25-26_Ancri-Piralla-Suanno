<!DOCTYPE html>
<?php
require_once("../backend/auth_check.php");
require_once("../common/functions.php");

if (!isset($_GET["aula"])) {header("Location: http://localhost/frontend/prenotazioni_aula.php?aula=T01");}
$aula = $_GET["aula"];
?>
<html>
    <head>
        <title>Aula <?php echo $aula ?></title>
        <link href="../css/custom_style.css" rel="stylesheet">
        <link rel="stylesheet" href="..\css\fontawesome.css">
        <link rel="stylesheet" href="..\css\templatemo-574-mexant.css">
        <link rel="stylesheet" href="..\css\owl.css">
        <link rel="stylesheet" href="..\css\animate.css">
        
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        <script src="../js/calendarManager.js"></script>
    </head>
    <body id="prenotazioni-aula">
        <?php
        include '../common/navbar.php'
        ?>
        <div class="page-heading">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="header-text">
                            <h2>Prenotazioni aula <span id="roomname"><?php echo $aula ?></span></h2>
                            <div class="div-dec"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <section class="services">
            <div class="schedule service-item" style="grid-area: 1/1/span 2/1;">
                <div style="display: flex; grid-area: 1/1/span 1/1;">
                    <button onclick="changeWeek(-1)">-1</button>
                    <h4>Settimana <span id="weekname"></span></h4>
                    <button onclick="changeWeek(1)">+1</button>
                </div>
                <div class="timetable" id="timetbl">
                    <?php
                    getIndexes();
                    ?>
                </div>
            </div>
            </div>
            <div class="orange-button" style="grid-area: 2/2/span 1/span 1;">
                <a href="..\inde.php" id="gestione-button" style="width:100%; place-content: center;">
                    Gestione prenotazioni</a>
            </div>
        </section>
        <?php
        include '../common/footer.php';
        ?>
    </body>
</html>