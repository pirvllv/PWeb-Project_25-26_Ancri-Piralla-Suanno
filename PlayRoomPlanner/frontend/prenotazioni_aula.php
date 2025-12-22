<!DOCTYPE html>
<?php
require_once "../backend/auth_check.php";
require_once "../common/functions.php";

if (!isset($_GET["aula"])) {header("Location: ../frontend/prenotazioni_aula.php?aula=T01");}
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
$_SESSION["aula"] = $_GET["aula"];
?>
<html>
    <head>
        <title>Aula <?php echo $_SESSION['aula'] ?></title>
        <link href="../css/custom_style.css" rel="stylesheet">
        <?php getCss(); ?>

        <script src="../js/calendarManager.js"></script>
        <script>
            window.sessionData = {
            aula: "<?php echo $_SESSION['aula']; ?>"
            };
        </script>
    </head>
    <body id="prenotazioni-aula">
        <?php
        include "../common/navbar.php";
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
                     <button id="change-week" class="green-button" onclick="changeWeek(-1,'room')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                          <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                        </svg></button>
                    <h4 id="weekname">Questa settimana</h4>
                    <button id="change-week" class="green-button" onclick="changeWeek(1, 'room')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-right-fill" viewBox="0 0 16 16">
                            <path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"/>
                        </svg></button>
                </div>
                <div class="timetable" id="timetbl">
                    <?php
                    getIndexes();
                    ?>
                </div>
            </div>
            </div>
            <?php
            if ($_SESSION["ruolo"]=="responsabile") {
                echo '<div class="orange-button" style="grid-area: 2/2/span 1/span 1;">
                            <a href="../frontend/gestionePrenotazioni.php" id="gestione-button" style="width:100%; place-content: center;">
                            Crea prenotazione</a>
                        </div>';
            }
            ?>
        </section>
        <?php
        include "../common/footer.php";
        ?>
    </body>
</html>