<!DOCTYPE html>
<?php
include "../config.php";
require_once $root."/backend/auth_check.php";
require_once $root."/common/functions.php";

if (!isset($_GET["aula"])) {header("Location: /PlayRoomPlanner/frontend/prenotazioni_aula.php?aula=T01");}
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
$_SESSION["aula"] = $_GET["aula"];
?>
<html>
    <head>
        <title>Aula <?php echo $_SESSION['aula'] ?></title>
        <link href="/PlayRoomPlanner/css/custom_style.css" rel="stylesheet">
        <?php getCss(); ?>

        <script src="/PlayRoomPlanner/js/calendarManager.js"></script>
        <script>
            window.sessionData = {
            aula: "<?php echo $_SESSION['aula']; ?>"
            };
        </script>
    </head>
    <body id="prenotazioni-aula">
        <?php
        include $root."/common/navbar.php";
        ?>
        <div class="page-heading">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="header-text">
                            <h2>Prenotazioni aula <span id="roomname"><?php echo $_SESSION['aula'] ?></span></h2>
                            <div class="div-dec"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <section class="services">
            <div class="schedule service-item" style="grid-area: 1/1/span 2/1;">
                <div style="display: flex; grid-area: 1/1/span 1/1;">
                    <button onclick="changeWeek(-1,'room')">-1</button>
                    <h4 id="weekname">Questa settimana</h4>
                    <button onclick="changeWeek(1,'room')">+1</button>
                </div>
                <div class="timetable" id="timetbl">
                    <?php
                    getIndexes();
                    ?>
                </div>
            </div>
            </div>
            <div class="orange-button" style="grid-area: 2/2/span 1/span 1;">
                <a href="/PlayRoomPlanner/frontend/gestionePrenotazioni.php" id="gestione-button" style="width:100%; place-content: center;">
                    Gestione prenotazioni</a>
            </div>
        </section>
        <?php
        include $root."/common/footer.php";
        ?>
    </body>
</html>