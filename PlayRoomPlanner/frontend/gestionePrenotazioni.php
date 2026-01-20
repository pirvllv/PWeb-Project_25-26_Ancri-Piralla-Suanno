<?php

session_start();
require_once("../backend/auth_check.php");

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

?>

<?php
if(!isset($_SESSION) || $_SESSION['logged_in'] == false || $_SESSION['responsabile'] == false) {
    http_response_code(403);
    ?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>403 Forbidden</title>
    </head>
    <body>
        <h2>Errore 403: Forbidden</h2>
        <p>Non hai i permessi necessari per visualizzare la pagina.</p>
        <a href="../index.php">Torna alla home</a>
    </body>
<?php
} else {
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/fontawesome.css">
    <link rel="stylesheet" href="../css/templatemo-574-mexant.css">
    <link rel="stylesheet" href="../css/owl.css">
    <link rel="stylesheet" href="../css/animate.css">
    <link rel="stylesheet" href="../css/custom_style_carlo.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css">
    <script src="../js/gestionePrenotazioni.js"></script>
    <title>Gestione prenotazioni</title>
</head>

<body>
    <?php include "../common/navbar.php"; ?>


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

    <div class="container" style="margin-top: 50px; margin-bottom: 50px;">
        <div class="section-prenotazioni">
            <div class="form-prenotazioni">
                <div class="crea-prenotazione">
                    <button class="orange-button" style="margin-bottom: 20px;" onclick="mostraForm('crea')">Crea
                        prenotazione</button>

                    <form id="crea" style="display:none; padding-top: 50px;">
                        <div class="container">
                            <h5 style="margin-bottom: 20px;">Crea prenotazione</h5>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Data di prenotazione</label>
                                <div class="col-sm-9">
                                    <input type="date" name="DataPren" class="form-control" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Ora di inizio</label>
                                <div class="col-sm-9">
                                    <select name="OraInizio" class="form-control" required>
                                        <?php
                                        $start = strtotime("09:00");
                                        $end = strtotime("22:30");
                                        for ($t = $start; $t <= $end; $t += 1800) {
                                            $time = date("H:i", $t);
                                            echo "<option value='$time'>$time</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Ora di fine</label>
                                <div class="col-sm-9">
                                    <select name="OraFine" class="form-control" required>
                                        <?php
                                        $start = strtotime("09:30");
                                        $end = strtotime("23:00");
                                        for ($t = $start; $t <= $end; $t += 1800) {
                                            $time = date("H:i", $t);
                                            echo "<option value='$time'>$time</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Numero aula</label>
                                <div class="col-sm-9">
                                    <select id="crea-aula" name="NumAula" class="form-control" required>
                                        <option value="">Seleziona aula</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Attività</label>
                                <div class="col-sm-9">
                                    <input type="text" name="Attivita" class="form-control" required>
                                </div>
                            </div>
                            <button class="green-button" type="submit">Crea</button>
                            <button class="red-button" type="reset" onclick="mostraForm(null)">Annulla</button>
                        </div>
                    </form>

                    <form id="modifica" style="display:none; margin-top: 30px;">
                        <input type="hidden" id="modifica-id" name="IDPrenotazione">
                        <div class="container">
                            <h5 style="margin-bottom: 20px;">Modifica prenotazione</h5>
                            <div id="modifica-dettagli" style="margin-bottom: 20px; padding: 10px; background-color: #f5f5f5; border-radius: 4px;">
                                <small id="modifica-testo-dettagli"></small>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Data di prenotazione</label>
                                <div class="col-sm-9">
                                    <input type="date" id="modifica-data" name="DataPren" class="form-control" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Ora di inizio</label>
                                <div class="col-sm-9">
                                    <select id="modifica-oraInizio" name="OraInizio" class="form-control" required>
                                        <?php
                                        $start = strtotime("09:00");
                                        $end = strtotime("22:30");
                                        for ($t = $start; $t <= $end; $t += 1800) {
                                            $time = date("H:i", $t);
                                            echo "<option value='$time'>$time</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Ora di fine</label>
                                <div class="col-sm-9">
                                    <select id="modifica-oraFine" name="OraFine" class="form-control" required>
                                        <?php
                                        $start = strtotime("09:30");
                                        $end = strtotime("23:00");
                                        for ($t = $start; $t <= $end; $t += 1800) {
                                            $time = date("H:i", $t);
                                            echo "<option value='$time'>$time</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Numero aula</label>
                                <div class="col-sm-9">
                                    <select id="modifica-aula" name="NumAula" class="form-control" required>
                                        <option value="">Seleziona aula</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Attività</label>
                                <div class="col-sm-9">
                                    <input type="text" id="modifica-attivita" name="Attivita" class="form-control" required>
                                </div>
                            </div>
                            <button class="green-button" type="submit">Salva modifiche</button>
                            <button class="red-button" type="reset" onclick="mostraForm(null)">Annulla</button>
                        </div>
                    </form>

                    <form id="invita" style="display:none; margin-top: 30px; padding: 10px;">
                        <h5 id="titolo-prenotazione-inviti" style="margin-bottom:30px;"></h5>
                        <input type="hidden" id="id-prenotazione-invito">
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">E-mail utente</label>
                            <div class="col-sm-6">
                                <input type="text" id="invito-email" name="Email-utente" class="form-control">
                            </div>
                            <div class="col-sm-3">
                                <button type="button" class="orange-button" onclick="controllaUtente('invito-email')">Aggiungi</button>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-12" id="block-lista-da-invitare" style="margin-bottom: 20px; padding: 10px; background-color: #f5f5f5; border-radius: 4px;">
                                <small id="lista-da-invitare"></small>
                            </div>
                            <div class="col-sm-12" style="margin-bottom: 20px; padding: 10px; background-color: #f5f5f5; border-radius: 4px;">
                                <small id="lista-invitati"></small>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-9">
                                <button class="green-button" type="button" onclick="mostraForm(null); invitaUtenti(); svuotaListaDaInvitare(); svuotaListaInvitati();">Invita</button>
                                <button class="orange-button" type="reset" onclick="svuotaListaDaInvitare();">Resetta</button>
                                <button class="red-button" type="reset" onclick="mostraForm(null); svuotaListaDaInvitare(); svuotaListaInvitati();">Annulla</button>
                            </div>
                        </div>
                    </form>

                    <div style="margin-top: 30px;">
                        <h5>Prenotazioni effettuate</h5>
                        <div id="lista-prenotazioni"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include "../common/footer.php"; ?>
</body>

</html>
<?php } ?>