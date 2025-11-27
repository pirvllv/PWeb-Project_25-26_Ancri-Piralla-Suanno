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
            <div class="table-sched">
                <div class="header">
                    <?php
                    for ($g = 0; $g < count($weekdays); $g++) {

                        echo "<div style=\"grid-column:".($g+1)."\">".$weekdays[$g]."</div>";

                    }
                    ?>
                </div>
                <div class="timetable">
                    <?php
                    echo(table_from_schedule($sched, 8, 18));
                    ?>
                </div>

            </div>
            <div class="scroll-sched">
                <?php
                        //print_r($sched);
                        for ($g = 0; $g < count($sched); $g++) {
                            echo "<p>".$weekdays[$g]."</p>";
                            foreach ($sched[$g] as $att) {
                                echo "<p class=\"act ".$att["stato"]."\"p>";
                                echo $att["orainizio"]." - ".$att["attivita"]."</p>";
                            }
                            echo "<br>";

                        }
                ?>
            </div>
        </div>
    </body>
</html>