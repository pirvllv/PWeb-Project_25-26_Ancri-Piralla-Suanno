<!DOCTYPE html>
<?php
require_once("../Common/functions.php");
require_once("../backend/bookings_API.php");
$todaystamp = strtotime("10 november 2025", time());
$mondayStamp = getMondayStamp($todaystamp);

$aula = "T01";
$inviti = get_bookings($aula, $mondayStamp, "room");
$sched = get_room_schedule($inviti);

$a = strtotime("03-04-2025"); $b = 3600; 
//print_r($sched);
//die(date("d-m-Y H:i", $a + $b));

$weekdays = array("Lunedì", "Martedì", "Mercoledì", "Giovedì", "Venerdì", "Sabato", "Domenica");
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
    </head>
    <body class="prenotazioni-aula">
        <?php
        include '../common/navbar.php'
        ?>
        <div class="page-heading">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="header-text">
                            <h2>Prenotazioni aula <?php echo $aula ?></h2>
                            <div class="div-dec"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <section class="services">
            <div class="schedule service-item" style="grid-area: 1/1/span 2/1;">
                <div class="timetable" >
                    <?php
                    for ($g = 0; $g < count($weekdays); $g++) {

                        echo "<div class=\"cell index\"; style=\"grid-area: 1 / ".($g+2)."/ 3 / span 1;\">".$weekdays[$g]."</div>";
                        //<div class="cell index" style="grid-area: 1/2">Lunedì</div>

                    }

                    for ($h = 0; $h < 11; $h++) {

                        echo "<div class=\"cell index\"; style=\"grid-area: ".(2*($h+1)+1)."/"."1/ span 2 /1;\">".($h+8).":00</div>";
                            //<div class="cell index" style="grid-area: 1/2">Lunedì</div>

                    }
                    echo(table_from_schedule($sched, 8, 18));
                    ?>
                </div>
                <div class="scroll-sched" style= "grid-area: 2/2/2/2;">
                    <?php
                            //print_r($sched);
                            foreach ($sched as $g => $day) {
                                echo "<div class=\"cell index\">".$weekdays[$g]."</div>";
                                foreach ($sched[$g] as $att) {
                                    echo displayAtt($att);
                                }
                                echo "<br>";

                            }
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