<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    require_once "../backend/connessione.php";

    $hostname = "localhost";
    $username = "root";
    $password = "";
    $dbname   = "mio_db";

    $email = "mario.rossi@email.com";

    $cid = connessione($hostname, $username, $password, $dbname);

    $stmt = $cid->prepare("SELECT IDPrenotazione, DataPren FROM Prenotazione WHERE ResponsabileEmail = ? ORDER BY DataPren DESC");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    if ($cid) {
        $cid->close();
    }

?>


<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    

        <!-- Bootstrap core CSS -->
        <link href="../css/bootstrap.min.css" rel="stylesheet">

        <!-- Additional CSS Files -->
        <link rel="stylesheet" href="../css/fontawesome.css">
        <link rel="stylesheet" href="../css/templatemo-574-mexant.css">
        <link rel="stylesheet" href="../css/owl.css">
        <link rel="stylesheet" href="../css/animate.css">
        <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css">

        <title>Gestione prenotazioni</title>
    </head>

    <body>


        <div class="titolo">
            <h1>Gestore prenotazioni</h1>
        </div>

        <div class="gestore-prenotazioni">
            <div class="vista-prenotazioni">
                <div class="box-prenotazioni">
                    <div class="form-prenotazioni">
                        <h3>Seleziona azione</h3>
                        <div class="form-prenotazioni">
                            <button class="green-button" onclick="mostraForm('crea')">Crea prenotazione</button>
                            <button class="green-button" onclick="mostraForm('modifica')">Modifica prenotazione</button>
                            <button class="green-button" onclick="mostraForm('elimina')">Elimina prenotazione</button>

                            <!-- Form Crea -->
                            <form id="crea" action="../backend/api-gestionePrenotazioni.php" method="post" style="display:none;">
                                <input type="date" name="DataPren">Data di prenotazione
                                <input type="time" name="OraInizio">Ora di inizio
                                <input type="time" name="OraFine">Ora di fine
                                <input type="text" name="NumAula">Numero aula
                                <input type="text" name="Attivita">Attività<br>
                                <button class="green-button" type="submit" name="azione" value="crea">Crea</button>
                                <button class="red-button" type="reset" name="azione">Annulla</button>
                            </form>

                            <!-- Form Modifica -->
                            <form id="modifica" action="../backend/api-gestionePrenotazioni.php" method="post" style="display:none;">
                                <input name="IDPrenotazione">ID prenotazione</textarea><br>
                                <button class="green-button" type="submit" name="azione" value="modifica">Modifica</button>
                                <button class="red-button" type="reset" name="azione">Annulla</button>
                            </form>

                            <!-- Form Elimina -->
                            <form id="elimina" action="../backend/api-gestionePrenotazioni.php" method="post" style="display:none;">
                                <input name="IDPrenotazione">ID prenotazione</textarea><br>
                                <button class="green-button" type="submit" name="azione" value="elimina">Elimina</button>
                                <button class="red-button" type="reset" name="azione">Annulla</button>
                            </form>

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
            
        </div>
        <script href="../js/gestionePrenotazioni.js"></script>

            

    </body>

</html>