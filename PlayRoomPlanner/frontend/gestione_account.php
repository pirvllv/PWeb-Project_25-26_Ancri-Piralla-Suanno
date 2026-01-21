<!DOCTYPE html>
<?php
require_once "../backend/auth_check.php";
require_once "../common/functions.php";
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

?>
<html>
    <head>
        <title>Gestione account</title>
        <link href="../css/custom_style.css" rel="stylesheet">
        <?php getCss(); ?>
        <script src="../js/userData.js"></script>
        <script src="../common/functions.js"></script>
        <script>
          window.sessionData = {
            username: "<?php echo $_SESSION['user']; ?>"
          };
        </script>
    </head>
    <body id="gestione-account">
        <?php
        include "../common/navbar.php";
        ?>
        <div class="page-heading">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="header-text">
                            <h2>Gestione account</h2>
                            <div class="div-dec"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="green-button" style="grid-area: 1/2/span 1/span 1; margin-top: 30px; margin-left: 30px;">
            <a href="../frontend/area_personale.php" id="gestione-button" style="width:100%; place-content: center;">Area Personale</a>
        </div>
        <div class="green-button" style="grid-area: 1/2/span 1/span 1; margin-top: 30px;">
            <a href="../frontend/sale_prova.php" id="gestione-button" style="width:100%; place-content: center;">Sale Prova</a>
        </div>
        <?php
        if ($_SESSION["responsabile"]) {
            echo '<div class="orange-button" style="grid-area: 2/2/span 1/span 1;">
                    <a href="../frontend/gestionePrenotazioni.php" id="gestione-button" style="width:100%; place-content: center;">Gestione prenotazioni</a>
                  </div>';
            }
        ?>
        <section class="services">
            <div class="account-data-form service-item">
                <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                      <div class="section-heading">
                        <h6>Ciao <?php echo $_SESSION['nome']?></h6>
                        <h4>I tuoi dati</h4>
                      </div>
                    </div>
                    <div class="col-lg-10 offset-lg-1">
                      <?php getUserDataForm("account"); ?>
                    </div>
                </div>
            </div>
        </section>
        <?php
        include "../common/footer.php";
        ?>
    </body>
</html>