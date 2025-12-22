<?php
require_once "../backend/auth_check.php";
require_once "../common/functions.php";
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Area personale</title>

        <?php getCss(); ?>
        <link rel="stylesheet" href="../css/custom_style.css">

        <script src="../js/calendarManager.js"></script>
        <script>
          window.sessionData = {
            username: "<?php echo $_SESSION['user']; ?>"
          };
        </script>
    </head>
    <body id="area-personale">
        <?php
        include "../common/navbar.php"; 
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
                <div style="display:flex; grid-area: 1/1/span 1/1;">
                    <button id="change-week" class="green-button" onclick="changeWeek(-1,'week')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                          <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                        </svg></button>
                    <h4 id="weekname">Questa settimana</h4>
                    <button id="change-week" class="green-button" onclick="changeWeek(1, 'week')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-right-fill" viewBox="0 0 16 16">
                            <path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"/>
                        </svg></button>
                </div>
                <div style="grid-area: 1/2/span 1/2;">
                    <h4 style="text-align: center;">Inviti</h4>
                </div>
                <div class="timetable" id="timetbl">
                    <?php
                    getIndexes();
                    ?>
                </div>
                <div class="scroll-invites" id="scroll" style= "grid-area: 2/2/2/2;"></div>
            </div>
            <div class="green-button" style="grid-area: 1/2/span 1/span 1;">
                <a href="../frontend/gestione_account.php" id="gestione-button" style="width:100%; place-content: center;">
                    Gestione account</a>
            </div>
            <?php
            if ($_SESSION["responsabile"]) {
                echo '<div class="orange-button" style="grid-area: 2/2/span 1/span 1;">
                            <a href="../frontend/gestionePrenotazioni.php" id="gestione-button" style="width:100%; place-content: center;">
                            Gestione prenotazioni</a>
                        </div>';
            }
            ?>
        </section>
        <?php
        include "../common/footer.php"; 
        ?>
    </body>
</html>