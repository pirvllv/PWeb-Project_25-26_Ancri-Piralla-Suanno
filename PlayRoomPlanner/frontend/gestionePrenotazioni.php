<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    require_once "../backend/connessione.php";

    $hostname = "localhost";
    $username = "root";
    $password = "";
    $dbname   = "mio_db";

    $cid = connessione($hostname, $username, $password, $dbname);

    $sala = null;
    $datiSala = [];
    $datiPren = [];

    // se l’utente ha inviato la form
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $sala = $_POST['sala-prove'] ?? null;

        if ($sala) {
            $stmt = $cid->prepare("SELECT Capienza, SettoreNome FROM SalaProve WHERE NumAula = ?");
            $stmt->bind_param("s", $sala);
            $stmt->execute();
            $stmt->bind_result($capienza, $settoreNome);

            while ($stmt->fetch()) {
                $datiSala[] = [
                    "Capienza" => $capienza,
                    "SettoreNome" => $settoreNome
                ];
            }  

            $stmt = $cid->prepare("SELECT IDPrenotazione, Attivita, ResponsabileEmail FROM Prenotazione
                                    WHERE NumAula = ? AND DataPren = ?");
            $stmt->bind_param("ss", $sala, $dataSelezionata);
            $stmt->execute();
            $stmt->bind_result($IDPren, $attivita, $respEmail);

            while ($stmt->fetch()) {
                $datiPren[] = [
                    "IDPrenotazione" => $IDPren,
                    "Attivita" => $attivita,
                    "ResponsabileEmail" => $respEmail

                ];
            }
        }
    }

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
        <div class="container-calendario">
            <div class="testo-calendario">
                <h1>Gestore prenotazioni</h1>
                <p>Scegli la settimana da gestire</p>
            </div>

            <div class="boxSettimana">
                <div class="nav-settimana">
                    <button id="btn-sett-prec" class="orange-button">Prec</button>
                    <span id="label-sett"></span>
                    <button id="btn-sett-succ" class="orange-button">Succ</button>
                </div>

                <ul class="settimana grid-settimana" id="settimana-nome">
                    <li>Lun</li><li>Mar</li><li>Mer</li><li>Gio</li><li>Ven</li><li>Sab</li><li>Dom</li>
                </ul>

                <ul class="settimana grid-settimana" id="settimana-value"></ul>
            </div>
        </div>
        <script src="../js/calendario.js"></script>
        

        <div class="select-sala">
            <p>Scegli la sala prove</p>

            <form method="POST">
                <select name="sala-prove" onchange="this.form.submit()">
                    <option value="">Seleziona</option>
                    <option value="D01">Danza moderna - D01</option>
                    <option value="M01">Musica Classica - M01</option>
                </select>
            </form>

            <?php if ($sala && $datiSala): ?>
            <h2>Dati sala: <?php echo htmlspecialchars($sala); ?></h2>

            <?php foreach ($datiSala as $row): ?>
                <p>Capienza: <?php echo $row['Capienza']; ?></p>
                <p>Nome: <?php echo $row['SettoreNome']; ?></p>
                <hr>
            <?php endforeach; ?>

            <?php elseif ($sala): ?>
                <p>Nessun dato trovato per questa sala.</p>
            <?php endif; ?>

            <?php if ($datiPren): ?>
            <h2>Dati prenotazione:</h2>

            <?php foreach ($datiPren as $row): ?>
                <p>Attivita': <?php echo $row['Attivita']; ?></p>
                <p>ID: <?php echo $row['IDPrenotazione']; ?></p>
                <p>Email responsabile: <?php echo $row['ResponsabileEmail']; ?></p>
                <hr>
            <?php endforeach; ?>
            <?php elseif ($datiPren): ?>
                <p>Nessuna prenotazione per il giorno selezionato.</p>
            <?php endif; ?>

        </div>

    </body>

</html>