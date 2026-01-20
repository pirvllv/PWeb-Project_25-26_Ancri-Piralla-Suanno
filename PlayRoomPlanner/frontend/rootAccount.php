<?php

session_start();
require_once("../backend/auth_check.php");
require_once("../common/functions.php");

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
    <link href="../css/custom_style.css" rel="stylesheet">
    <?php getCss(); ?>
    <link rel="stylesheet" href="../css/fontawesome.css">
    <link rel="stylesheet" href="../css/templatemo-574-mexant.css">
    <link rel="stylesheet" href="../css/owl.css">
    <link rel="stylesheet" href="../css/animate.css">
    <link rel="stylesheet" href="../css/custom_style_carlo.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css">
    <script src="../js/gestionePrenotazioni.js"></script>
    <script src="../js/userData.js"></script>
    <script src="../common/functions.js"></script>
    <script>
          window.sessionData = {
            username: "<?php echo $_SESSION['user']; ?>"
          };
    </script>
    <script src="../js/rootAccount.js"></script>
    <title>Account admin</title>
</head>

<body id="root-account">
    <?php include "../common/navbar.php"; ?>


    <div class="page-heading chi-siamo-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="header-text">
                        <h2>Account amministratore</h2>
                        <div class="div-dec"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="options bar" style="margin-top: 50px; margin-bottom: 50px;">
            <div class="row mb-3">
                <div class="col-sm-3">
                    <button class="orange-button" id="button-manager-responsabili" onclick="mostraSezione('manager-responsabili')">Responsabili corsi</a>
                </div>
                <div class="col-sm-3">
                    <button class="orange-button" id="button-edit-user-data" onclick="mostraSezione('edit-user-data')">Dati utenti e ruoli</a>
                </div>
                <div class="col-sm-3">
                    <button class="orange-button" id="button-booking-manager" onclick="mostraSezione('booking-manager')">Prenotazioni</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container" id="manager-responsabili" style="display:none; margin-top:50px; margin-bottom:50px;">
        <h5>Settori e responsabili</h5>
        <div id="lista-responsabili">
        </div>
    </div>

    <section class="services">
        <div class="account-data-form service-item" id="edit-user-data" style="display:none;">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                <form id="seleziona-utente-form">
                    <label>Email utente da modificare</label>
                    <input type="text" id="email-da-verificare" required>
                    <button class="green-button" type="submit">Seleziona</button>
                </form>
                <div id="form-modifica-user" style="display:none;">
                    <div class="section-heading">
                        <h6 id="titolo-nome-utente"></h6>
                        <h4>Dati</h4>
                    </div>
                
                    <div class="col-lg-10 offset-lg-1">
                        <?php getUserDataForm("account"); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container" id="booking-manager" style="display:none; margin-bottom: 50px;">
        <div class="section-prenotazioni">
            <div class="form-prenotazioni">
                <div class="crea-prenotazione">
                    <button class="orange-button" style="margin-bottom: 20px;" onclick="mostraForm('crea')">Crea
                        prenotazione</button>

                    <form id="crea" style="display:none;">
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
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Responsabile</label>
                                <div class="col-sm-9">
                                    <input type="text" name="Responsabile" class="form-control" required>
                                </div>
                            </div>
                            <button class="green-button" type="submit">Crea</button>
                            <button class="red-button" type="reset" onclick="mostraForm(null)">Annulla</button>
                        </div>
                    </form>

                    <form id="modifica" style="display:none; padding-top: 80px;">
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

                    <form id="invita" style="display:none; padding-top: 80px;">
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
                            <div class="col-sm-12" style="margin-bottom: 20px; padding: 10px; background-color: #f5f5f5; border-radius: 4px;">
                                <small id="lista-invitati"></small>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-9">
                                <button class="green-button" type="button" onclick="mostraForm(null); invitaUtenti(document.getElementById('id-prenotazione-invito').value)">Invita</button>
                                <button class="orange-button" type="reset" onclick="svuotaLista()">Resetta</button>
                                <button class="red-button" type="reset" onclick="mostraForm(null); svuotaLista()">Annulla</button>
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

</body>
<?php } ?>