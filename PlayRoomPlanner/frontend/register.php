<!DOCTYPE html>
<?php
require_once ("../common/functions.php");

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (isset($_SESSION['logged_in'])) {
  if ($_SESSION['logged_in'] === true) {
    header("Location: ../index.php");
    exit;
  }
}

?>
<html>
    <head>
        <title>Nuovo account</title>
        <link href="../css/custom_style.css" rel="stylesheet">
        <?php getCss(); ?>
        <script src="../js/userData.js"></script>
    </head>
    <body id="nuovo-account">
        <?php
        include "../common/navbar.php";
        ?>
        <div class="page-heading">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="header-text">
                            <h2>Nuovo account</h2>
                            <div class="div-dec"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <section class="services">
            <div class="account-data-form service-item">
                <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                      <div class="section-heading">
                        <h4>Nuovo utente</h4>
                      </div>
                    </div>
                    <div class="col-lg-10 offset-lg-1">
                      <?php getUserDataForm("register"); ?>
                    </div>
                </div>
            </div>
        </section>
        <?php
        include "../common/footer.php";
        ?>
    </body>
</html>