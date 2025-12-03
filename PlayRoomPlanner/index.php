<!DOCTYPE html>
<html lang="it">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet"
		href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap">
	<link rel="stylesheet" href="/PlayRoomPlanner/css/bootstrap.min.css">
	<link rel="stylesheet" href="/PlayRoomPlanner/css/fontawesome.css">
	<link rel="stylesheet" href="/PlayRoomPlanner/css/templatemo-574-mexant.css">
	<link rel="stylesheet" href="/PlayRoomPlanner/css/owl.css">
	<link rel="stylesheet" href="/PlayRoomPlanner/css/animate.css">
	<link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css">

	<!-- NOME DEL FILE DA CAMBIARE DOPO MERGE -->
	<link rel="stylesheet" href="/PlayRoomPlanner/css/custom_style_carlo.css">
	<title>PlayRoomPlanner - Home</title>
</head>

<body>
	<?php
	include 'common/navbar.php';
	?>

	<div class="swiper-container" id="top">
		<div class="swiper-wrapper">
			<div class="swiper-slide">
				<div class="slide-inner" style="background-image: url(immagini/home_bg.jpg);">
					<div class="container">
						<div class="row">
							<div class="col-lg-8">
								<div class="header-text">
									<h2>Benvenuto su <em>PlayRoomPlanner</em></h2>
									<div class="div-dec"></div>
									<p>Il servizio ideale per organizzare e gestire le tue stanze per provare. Prenota e pianifica eventi
										nelle nostre sale.<br>Scorri verso il basso per scoprire i nostri servizi!</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	

	<section class="services section-services-padding-bottom">
		<div class="container">
			<div class="row">
				<!-- Danza -->
				<div class="col-lg-12">
					<a href="/PlayRoomPlanner/frontend/sale_prova.php#danza">
						<div class="service-item">
							<div class="row">
								<div class="col-lg-4">
									<div class="icon">
										<img src="immagini/danza.jpg" alt="Danza">
									</div>
								</div>
								<div class="col-lg-8">
									<div class="right-content">
										<h4>Danza</h4>
										<p>Le nostre sale danza offrono spazi luminosi, pavimentazione ammortizzata, specchi a tutta parete
											e
											impianti audio professionali. Sono progettate per garantire comfort, sicurezza e la massima
											qualità
											di movimento, ideali per lezioni, prove, workshop e produzioni artistiche. Disponibili per scuole,
											compagnie e singoli professionisti.</p>
									</div>
								</div>
							</div>
						</div>
					</a>
				</div>

				<!-- Musica -->
				<div class="col-lg-12">
					<a href="/PlayRoomPlanner/frontend/sale_prova.php#musica">
						<div class="service-item">
							<div class="row">
								<div class="col-lg-4">
									<div class="icon">
										<img src="immagini/musica.jpg" alt="Musica">
									</div>
								</div>
								<div class="col-lg-8">
									<div class="right-content">
										<h4>Musica</h4>
										<p>Le nostre sale di registrazione e prova sono trattate acusticamente, dotate di strumentazione
											professionale e pensate per garantire un ascolto preciso e un ambiente di lavoro stabile. Offrono
											spazio, isolamento e qualità sonora per band, solisti, produttori e tecnici. Ideali per prove,
											sessioni di recording, pre-produzioni e progetti didattici.</p>
									</div>
								</div>
							</div>
						</div>
					</a>
				</div>

				<!-- Teatro -->
				<div class="col-lg-12">
					<a href="/PlayRoomPlanner/frontend/sale_prova.php#teatro">
						<div class="service-item">
							<div class="row">
								<div class="col-lg-4">
									<div class="icon">
										<img src="immagini/teatro.jpg" alt="Teatro">
									</div>
								</div>
								<div class="col-lg-8">
									<div class="right-content">
										<h4>Teatro</h4>
										<p>Il nostro teatro è uno spazio versatile, con palco attrezzato, illuminotecnica professionale e
											acustica curata. Offre un ambiente ideale per spettacoli, prove, rassegne, presentazioni ed eventi
											culturali. Pensato per compagnie, scuole e realtà artistiche che necessitano di una scena
											affidabile
											e pronta all'uso.</p>
									</div>
								</div>
							</div>
						</div>
					</a>
				</div>
			</div>
		</div>
	</section>


	<?php
	include 'common/footer.php';
	?>
</body>

</html>