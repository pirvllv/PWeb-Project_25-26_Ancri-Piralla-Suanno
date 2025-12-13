<!DOCTYPE html>
<?php
require_once("../Common/functions.php");

$user = "chiara.lombardi@email.com"
?>
<html>
    <head>
        <title>Area personale</title>
        <link href="../css/custom_style.css" rel="stylesheet">
        <link rel="stylesheet" href="..\css\fontawesome.css">
        <link rel="stylesheet" href="..\css\templatemo-574-mexant.css">
        <link rel="stylesheet" href="..\css\owl.css">
        <link rel="stylesheet" href="..\css\animate.css">
        
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        <script src="../js/calendarManager.js"></script>
    </head>
    <body id="area-personale">
        <?php
        include '../common/navbar.php'
        ?>
        <div class="page-heading">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="header-text">
                            <h2>Area personale</h2>
                            <h2 id="username"><?php echo $user ?></h2>
                            <div class="div-dec"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <section class="services">
            <div class="schedule service-item" style="grid-area: 1/1/span 2/1;">
                <div style="display:flex; sgrid-area: 1/1/span 1/1;">
                    <button onclick="changeWeek(-1)">-1</button>
                    <h4>Settimana <span id="weekname"></span></h4>
                    <button onclick="changeWeek(1)">+1</button>
                </div>
                <div style="grid-area: 1/2/span 1/2;">
                    <h4 style="text-align: center;">Inviti</h4>
                </div>
                <div class="timetable" id="timetbl">
                    <?php
                    getIndexes();
                    ?>
                </div>
                <div class="scroll-invites" id="scroll" style= "grid-area: 2/2/2/2;">
                    <?php
                    foreach (getWeekdays() as $g => $day) {
                        echo "<div id=\"".$day."-cont\"></div><br>";
                    }
                    ?>
                </div>
            </div>
            <div class="green-button" style="grid-area: 1/2/span 1/span 1;">
                <a href="..\Frontend\gestione_account.php" id="gestione-button" style="width:100%; place-content: center;">
                    Gestione account</a>
            </div>
            <div class="orange-button" style="grid-area: 2/2/span 1/span 1;">
                <a href="../Frontend/gestionePrenotazioni.php" id="gestione-button" style="width:100%; place-content: center;">
                    Gestione prenotazioni</a>
            </div>
        </section>
        <?php
        include '../common/footer.php';
        ?>
    </body>
</html>