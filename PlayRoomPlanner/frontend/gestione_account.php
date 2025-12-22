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