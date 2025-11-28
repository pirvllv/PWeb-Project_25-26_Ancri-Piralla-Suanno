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
        <link href="../css/style.css" rel="stylesheet">
        <link href="../css/custom_style.css" rel="stylesheet">
    </head>
    <body>
        <?php
        echo getNavbar();
        ?>
        <div class="schedule">
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
                            echo "<p>".$weekdays[$g]."</p>";
                            foreach ($sched[$g] as $att) {
                                echo "<div class=\"cell ".$att["stato"]."\">";
                                echo $att["orainizio"]." - ".$att["attivita"]."</div>";
                            }
                            echo "<br>";

                        }
                ?>
            </div>
        </div>
    </body>
</html>