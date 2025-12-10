<?php
require_once '../common/connection.php';

function mostraSale($cid, $tipologia)
{
  $sql = "SELECT SP.NumAula, SP.Capienza, SP.SettoreNome 
            FROM SalaProve SP 
            JOIN Settore S ON SP.SettoreNome = S.Nome 
            WHERE S.Tipologia = '$tipologia'
            ORDER BY SP.NumAula ASC";

  $result = $cid->query($sql);

  if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $numAula = htmlspecialchars($row["NumAula"]);
      $capienza = htmlspecialchars($row["Capienza"]);
      $nomeSettore = htmlspecialchars($row["SettoreNome"]);

      echo <<<HTML
            <div class="col-lg-12">
						  <div class="service-item">
							  <div class="row">
								  <div class="col-lg-4">
									  <div class="icon">
										  <img src="/PlayRoomPlanner/immagini/sale_prova/{$tipologia}_{$numAula}.jpg" alt="Sala Prova {$numAula}">
					  				</div>
					  			</div>
						  		<div class="col-lg-8">
							  		<div class="right-content">
								  		<h4>Sala Prova {$numAula}</h4>
                      <p><strong>Tipologia:</strong> {$tipologia}</p>
                      <p><strong>Capienza:</strong> {$capienza} persone</p>
                      <p><strong>Settore:</strong> {$nomeSettore}</p>
									  </div>
								  </div>
							  </div>
						  </div>
				    </div>
HTML;
    }
  } else {
    echo '<div class="row"><div class="col-12"><p class="text-center text-muted">Nessuna sala disponibile per questa categoria al momento.</p></div></div>';
  }
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap">
  <link rel="stylesheet" href="/PlayRoomPlanner/css/bootstrap.min.css">
  <link rel="stylesheet" href="/PlayRoomPlanner/css/fontawesome.css">
  <link rel="stylesheet" href="/PlayRoomPlanner/css/templatemo-574-mexant.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="/PlayRoomPlanner/css/owl.css">
  <link rel="stylesheet" href="/PlayRoomPlanner/css/animate.css">
  <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css">

  <!-- NOME DEL FILE DA CAMBIARE DOPO MERGE -->
  <link rel="stylesheet" href="/PlayRoomPlanner/css/custom_style_carlo.css">

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