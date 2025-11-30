<!DOCTYPE html>
<?php
require_once("../Common/navbar.php");
require_once("../Common/functions.php");
$sched = get_user_schedule("pippo", date("d/m/Y"));
$weekdays = array("Lunedì", "Martedì", "Mercoledì", "Giovedì", "Venerdì", "Sabato", "Domenica");
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
    </head>
    <body>
        <?php
        echo getNavbar();
        ?>
        <div class="page-heading area-personale">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="header-text">
                            <h2>Area personale</h2>
                            <div class="div-dec"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <section class="services area-personale">
            <div class="area-personale service-item">
                <div class="schedule" style="grid-area: 1/1/span 2/1;">
                    <div style="grid-area: 1/1/span 1/2;">
                        <h4>Impegni settimanali</h4>
                    </div>
                    <div class="timetable">
                        <?php
                        for ($g = 0; $g < count($weekdays); $g++) {
    
                            echo "<div class=\"cell index\"; style=\"grid-area: 1 / ".($g+2)."/ 1 / ".($g+2).";\">".$weekdays[$g]."</div>";
                            //<div class="cell index" style="grid-area: 1/2">Lunedì</div>
    
                        }
    
                        for ($h = 0; $h < 11; $h++) {
    
                            echo "<div class=\"cell index\"; style=\"grid-area: ".($h+2)."/"."1/".($h+2)."/1;\">".($h+8)."</div>";
                                //<div class="cell index" style="grid-area: 1/2">Lunedì</div>
    
                        }
                        echo(table_from_schedule($sched, 8, 18));
                        ?>
                    </div>
                    <div class="scroll-sched">
                        <?php
                                //print_r($sched);
                                for ($g = 0; $g < count($sched); $g++) {
                                    echo "<div class=\"cell index\">".$weekdays[$g]."</div>";
                                    foreach ($sched[$g] as $att) {
                                        echo "<div class=\"cell ".$att["stato"]."\">";
                                        echo $att["orainizio"]." - ".$att["attivita"]."</div>";
                                    }
                                    echo "<br>";
    
                                }
                        ?>
                    </div>
                </div>
                <div class="green-button" style="grid-area: 1/2/span 1/span 1;">
                    <a href="..\inde.php" style="height: 100%; width:100%; place-content: center;">
                        Gestione account</a>
                </div>
                <div class="orange-button" style="grid-area: 2/2/span 1/span 1;">
                    <a href="..\inde.php" style="height: 100%; width:100%; place-content: center;">
                        Gestione prenotazioni</a>
                </div>
            </div>
        </section>
    </body>
</html>