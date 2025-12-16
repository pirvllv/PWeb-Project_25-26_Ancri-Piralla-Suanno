<?php
include "../config.php";
require_once $root."/backend/auth_check.php";
require_once $root."/common/functions.php";
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Area personale</title>

        <?php getCss(); ?>
        <link rel="stylesheet" href="/PlayRoomPlanner/css/custom_style.css">

        <script src="/PlayRoomPlanner/js/calendarManager.js"></script>
        <script>
          window.sessionData = {
            username: "<?php echo $_SESSION['user']; ?>"
          };
        </script>
    </head>
    <body id="area-personale">
        <?php
        include $root."/common/navbar.php"; 
        ?>
        <div class="page-heading">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="header-text">
                            <h2>Area personale</h2>
                            <h2><?php echo $_SESSION['nome']." ".$_SESSION['cognome']?></h2>
                            <div class="div-dec"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <section class="services">
            <div class="schedule service-item" style="grid-area: 1/1/span 2/1;">
                <div style="display:flex; sgrid-area: 1/1/span 1/1;">
                    <button onclick="changeWeek(-1,'week')">-1</button>
                    <h4 id="weekname">Questa settimana</h4>
                    <button onclick="changeWeek(1, 'week')">+1</button>
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
                        echo "<div id=\"".$day."-cont\"></div>";
                    }
                    ?>
                </div>
            </div>
            <div class="green-button" style="grid-area: 1/2/span 1/span 1;">
                <a href="/PlayRoomPlanner/frontend/gestione_account.php" id="gestione-button" style="width:100%; place-content: center;">
                    Gestione account</a>
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