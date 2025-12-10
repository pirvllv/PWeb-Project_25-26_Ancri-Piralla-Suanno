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
  
	<title>PlayRoomPlanner - Chi Siamo</title>
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
            <h2>Chi siamo</h2>
            <div class="div-dec"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <section class="top-section">
    <div class="container">
      <div class="row">
        <div class="col-lg-6">
          <div class="left-image">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d25975.874285814225!2d9.19222570577177!3d45.45474615288637!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4786c6f5a031ce81%3A0x84b353b81f0f353d!2suniMI%20%C2%B7%20Settore%20didattico%20Celoria!5e1!3m2!1sit!2sit!4v1764750478910!5m2!1sit!2sit" width="100%" height="450" style="border:0; border-radius: 20px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div>
        </div>
        <div class="col-lg-6 align-self-center">
          <div class="section-heading">
            <h4>PlayRoomPlanner</h4>
            <h2>Il nostro team</h2>
            <p>Siamo tre studenti di Informatica Musicale che hanno sviluppato questo applicativo web per il progetto di Programmazione per il Web.</p>
          </div>
          <hr>
          <div class="simple-content">
            <h4>La nostra Università</h4>
            <p>Attualmente studiamo nell'Università degli Studi di Milano Statale, nel dipartimento di Scienze e Tecnologie. Essa è situata in via Giovanni Celoria e comprende più settori didattici.</p>
            <br>
            <h4>La nostra Visione</h4>
            <p>Questo applicativo parte dalla necessità di prenotare delle sale prova in modo semplice e visualmente intuitivo.</p>
          </div>
        </div>
      </div>
    </div>
  </section>  

  <?php 
    include '../common/footer.php';
  ?>
</body>

</html>