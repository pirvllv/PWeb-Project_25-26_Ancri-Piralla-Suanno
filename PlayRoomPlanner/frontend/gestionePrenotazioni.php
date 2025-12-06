<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $email = "mario.rossi@email.com";

?>


<!DOCTYPE html>

<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">



    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="../css/fontawesome.css">
    <link rel="stylesheet" href="../css/templatemo-574-mexant.css">
    <link rel="stylesheet" href="../css/owl.css">
    <link rel="stylesheet" href="../css/animate.css">

    <!-- riga di inclusione del mio file css custom da cancellare prima del merge, faremo poi un unico file di stile custom -->
    <link rel="stylesheet" href="../css/custom_style_carlo.css">

    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css">

    <title>Gestione prenotazioni</title>
</head>

<body>

    <?php
    include "../common/navbar.php";
    ?>

    <div class="page-heading chi-siamo-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="header-text">
                        <h2>Gestione prenotazioni</h2>
                        <div class="div-dec"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container" style="margin-top: 50px; margin-bottom: 50px;" name="container-prenotazioni">
        <h4 class="section-heading">Seleziona azione</h4>
        <div class="section">
            <div class="form-prenotazioni">
                <div class="form-prenotazioni">
                    <div class="crea-prenotazione">
                        <button class="orange-button" style="margin-bottom: 30px;" onclick="mostraForm('crea')">Crea prenotazione</button>
                        <form id="crea" action="../backend/api-gestionePrenotazioni.php" method="post" style="display:none;">
                            <div class="container">
                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label">Data di prenotazione</label>
                                    <div class="col-sm-9">
                                        <input type="date" name="DataPren" class="form-control">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label">Ora di inizio</label>
                                    <div class="col-sm-9">
                                        <input type="time" name="OraInizio" class="form-control">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label">Ora di fine</label>
                                    <div class="col-sm-9">
                                        <input type="time" name="OraFine" class="form-control">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label">Numero aula</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="NumAula" class="form-control">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label">Attività</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="Attivita" class="form-control">
                                    </div>
                                </div>

                                <button class="green-button" type="submit" name="azione" value="crea">Crea</button>
                                <button class="red-button" type="reset" onclick="mostraForm('')">Annulla</button>
                            </div>

                        </form><br>
                    </div>

                    <div style="margin-top: 30px;">
                        <h5>Prenotazioni effettuate</h5>
                        <div id="lista-prenotazioni"">
                            Caricamento...
                        </div>

                        <div id="azioni-prenotazione" style="margin-top:15px; display:none;">
                            <h6 id="info-pren"></h6>

                            <form action="../backend/api-gestionePrenotazioni.php" method="post" style="display:inline;">
                                <input type="hidden" name="IDPrenotazione" id="id-mod">
                                <button class="green-button" type="submit" name="azione" value="modifica" onclick="mostraForm('modifica')">Modifica</button>
                            </form>

                            <form action="../backend/api-gestionePrenotazioni.php" method="post" style="display:inline;">
                                <input type="hidden" name="IDPrenotazione" id="id-del">
                                <button class="red-button" type="submit" name="azione" value="elimina" onclick="mostraForm('')">Elimina</button>
                            </form>
                        </div>
                    </div>

                    <script>
                        function mostraForm(idForm) {
                            // Nasconde tutte le form
                            document.querySelectorAll('form').forEach(f => f.style.display = 'none');
                            // Mostra solo quella selezionata
                            document.getElementById(idForm).style.display = 'block';
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
    <script src="../js/gestionePrenotazioni.js"></script>

    <?php
    include "../common/footer.php";
    ?>

</body>

</html>