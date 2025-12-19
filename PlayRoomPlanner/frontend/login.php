<!DOCTYPE html>

<?php
require_once '../backend/connection.php';
// session_start();
?>

<html lang="it">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap">
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/fontawesome.css">
  <link rel="stylesheet" href="../css/templatemo-574-mexant.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../css/owl.css">
  <link rel="stylesheet" href="../css/animate.css">
  <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css">

  <!-- NOME DEL FILE DA CAMBIARE DOPO MERGE -->
  <link rel="stylesheet" href="../css/custom_style_carlo.css">
  <title>PlayRoomPlanner - Login</title>
</head>

<body>
  <?php
  include '../common/navbar.php';
  ?>

  <div class="page-heading chi-siamo-header">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="header-text">
            <h2>Login</h2>
            <div class="div-dec"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <section class="top-section login-section">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-6">
          <div class="section-heading">
            <h4>Accedi al tuo Account</h4>
            <p>Inserisci le tue credenziali per accedere all'area riservata.</p>
          </div>
        </div>
        <div class="col-lg-10">
          <form id="contact" action="../backend/login_controller.php" method="post">
            <div class="row">
              <div class="col-lg-6">
                <fieldset>
                  <input type="email" name="email" id="email" placeholder="La tua Email..." required="">
                </fieldset>
              </div>
              <div class="col-lg-6">
                <fieldset>
                  <input type="password" name="password" id="password" placeholder="La tua Password..." required="">
                </fieldset>
              </div>
              <div class="col-lg-12">
                <fieldset>
                  <button type="submit" id="form-submit" class="orange-button">Accedi</button>
                </fieldset>
              </div>
              <div class="col-lg-12 mt-3 text-center">
                <p>Non hai un account? <a href="#">Registrati qui</a></p>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>

  <?php
  include '../common/footer.php';
  ?>
</body>

</html>