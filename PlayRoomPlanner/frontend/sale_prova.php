<?php
require_once '../backend/connection.php';
require_once '../common/functions.php';
?>

<!DOCTYPE html>
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
  <title>PlayRoomPlanner - Sale Prova</title>
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
            <h2>Le nostre sale prova</h2>
            <div class="div-dec"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <section class="services section-services-padding-bottom">
    <div class="container">

      <!-- Sezione Danza -->
      <div class="row mb-5">
        <div class="col-12">
          <h2 class="text-center mb-4" id="danza" style="color: #ff511a;">Danza</h2>
        </div>
        <?php mostraSale($cid, 'danza'); ?>
      </div>

      <hr>

      <!-- Sezione Musica -->
      <div class="row mb-5 mt-5">
        <div class="col-12">
          <h2 class="text-center mb-4" id="musica" style="color: #ff511a;">Musica</h2>
        </div>
        <?php mostraSale($cid, 'musica'); ?>
      </div>

      <hr>

      <!-- Sezione Teatro -->
      <div class="row mt-5">
        <div class="col-12">
          <h2 class="text-center mb-4" id="teatro" style="color: #ff511a;">Teatro</h2>
        </div>
        <?php mostraSale($cid, 'teatro'); ?>
      </div>

    </div>
  </section>

  <?php
  include '../common/footer.php';
  ?>
</body>

</html>