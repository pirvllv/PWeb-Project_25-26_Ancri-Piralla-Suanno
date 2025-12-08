<?php

require_once "../common/connection.php";

$cid = connessione($hostname, $username, $password, $dbname);

$responsabile_email = "mario.rossi@email.com";


/* 
if (!isset($_SESSION['ResponsabileEmail'])) {
    echo "<script>alert('Accesso negato');</script>";
    return;
} 
*/

if(!$cid) {
    echo "<script>alert('Connessione al database non riuscita');</script>";
}

if (isset($_POST['selPrenot'])){
    switch ($_POST['selPrenot']) {
            case 'crea':
                createReservation($cid, $_POST);
                break;
            case 'modifica':
                updateReservation($cid, $_POST);
                break;
            case 'elimina':
                deleteReservation($cid, $_POST);
                break;
            default:
                echo "<script>alert('Azione non valida');</script>";
    }
}

if (isset($_POST['azione']) && $_POST['azione'] === 'mostraPren') {

    $sql = "SELECT IDPrenotazione, DataPren, OraInizio, OraFine, NumAula, Attivita
            FROM Prenotazione
            WHERE ResponsabileEmail = ?
            ORDER BY DataPren, OraInizio";

    $stmt = $cid->prepare($sql);
    $stmt->bind_param("s", $responsabile_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<p>Nessuna prenotazione trovata.</p>";
        return;
    }

    // output HTML diretto
    while ($row = $result->fetch_assoc()) {

        // ogni prenotazione genera un DIV cliccabile
        echo "
        <div class='row mb-3' 
             data-id='{$row['IDPrenotazione']}'
             data-data='{$row['DataPren']}'
             data-ora-inizio='{$row['OraInizio']}'
             data-ora-fine='{$row['OraFine']}'
             data-aula='{$row['NumAula']}'
             data-attivita='{$row['Attivita']}'
             style='padding:8px; border-bottom:1px solid #ddd; cursor:pointer;'>
             
            <div class='col-sm-9'>
                {$row['IDPrenotazione']} | {$row['DataPren']} {$row['OraInizio']}-{$row['OraFine']} | Aula {$row['NumAula']}
            </div>

                <div id='azioni-prenotazione' style='margin-top:15px;' class='col-sm-3'>

                    <form action='../backend/api-gestionePrenotazioni.php' method='post' style='display:inline;'>
                    
                        <button class='green-button' style='padding: 8px 18px;' type='submit' name='azione' value='modifica' onclick='mostraForm('modifica')'>Modifica</button>
                    </form>

                    <form action='../backend/api-gestionePrenotazioni.php' method='post' style='display:inline;'>
                        <button class='red-button' style='padding: 8px 18px;' type='submit' name='azione' value='elimina' onclick='mostraForm('')'>Elimina</button>
                    </form>
                </div>
            </div>

        </div>
        ";
    }

    return;
}

function createReservation($cid, $data) {
    
    $sala = $data['NumAula'];
    $data_pren = $data['DataPren'];
    $attivita = $data['Attivita'];

    
    $ora_inizio = strtotime($data['OraInizio']);
    $ora_fine   = strtotime($data['OraFine']);

    $durata = ($ora_fine - $ora_inizio) / 60;

    $limite_start = strtotime("09:00");
    $limite_end   = strtotime("23:00");

    if ($ora_inizio < $limite_start || $ora_fine > $limite_end || $ora_fine <= $ora_inizio) {
        echo "<script>alert('Orario non valido');</script>";
        return;
    }

    $stmt = $cid->query("SELECT MAX(IDPrenotazione) AS max_id FROM Prenotazione");
    $row = $stmt->fetch_assoc();
    $id_max = $row['max_id'];
    
    $new_id = $id_max + 1;

    $sqlCheck = "
        SELECT COUNT(*) AS occupata
        FROM Prenotazione
        WHERE NumAula = ?
          AND DataPren = ?
          AND NOT (
                OraFine <= ?
            OR  OraInizio >= ?
          )
    ";

    $stmtCheck = $cid->prepare($sqlCheck);
    $ora_inizio_str = date("H:i", $ora_inizio);
    $ora_fine_str   = date("H:i", $ora_fine);

    $stmtCheck->bind_param(
        "ssss",
        $sala,
        $data_pren,
        $ora_inizio_str,
        $ora_fine_str
    );

    $stmtCheck->execute();
    $resCheck = $stmtCheck->get_result()->fetch_assoc();

    if ($resCheck['occupata'] > 0) {
        echo "<script>alert('Sala già occupata');</script>";
        return;
    }

    $sqlInsert = "
        INSERT INTO Prenotazione 
        (IDPrenotazione, DataPren, OraInizio, OraFine, Attivita, NumAula, ResponsabileEmail)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ";

    $stmtInsert = $cid->prepare($sqlInsert);

    global $responsabile_email;

    $stmtInsert->bind_param(
        "issssss",
        $new_id,
        $data_pren,
        $ora_inizio_str,
        $ora_fine_str,
        $attivita,
        $sala,
        $responsabile_email
    );


    $stmtInsert->execute();

    echo "<script>alert('Prenotazione avvenuta con successo');</script>";
}


function updateReservation($cid, $data) {
    $id_prenotazione = $data['IDPrenotazione'];
    $sala = $data['NumAula'];
    // $responsabile_email = $_SESSION['ResponsabileEmail'];
    $data_pren = $data['DataPren'];
    $ora_inizio = (int)$data['OraInizio'];  // Converte "09:30" a 0 (int conversion di stringa)
    $ora_fine = (int)$data['OraFine'];      // Stesso problema
    $attivita = $data['Attivita'];

    $durata = $ora_fine - $ora_inizio;

    if ($ora_inizio < 9 || $ora_fine > 23 || $durata <= 0) {
        throw new Exception("Orario non valido");
    }

    $sqlCheck = "SELECT COUNT(*) as occupata 
                 FROM Prenotazione
                 WHERE NumSal = :sala 
                 AND DataPren = :data_pren
                 AND NOT (
                    OraFine <= :OraInizio
                    OR OraInizio >= :OraFine
                 )";
                 
    $stmtCheck = $cid->prepare($sqlCheck);
    $stmtCheck->execute([
        ':id_pren' => $id_prenotazione,
        ':sala' => $sala,
        ':data_pren' => $data_pren,
        ':ora_inizio' => $ora_inizio,
        ':ora_fine' => $ora_fine,
        ':attivita' => $attivita
    ]);

    if ($stmtCheck->fetch()['occupata'] > 0) {
        echo "<p>Sala già occupata</p>";
        return;
    }

    $sqlUpdate = "UPDATE Prenotazione 
                  SET NumAula = :sala, 
                      DataPren = :data_pren, 
                      OraInizio = :ora_inizio, 
                      OraFine = ::OraFine, 
                      Attivita = :attivita 
                  WHERE IDPrenotazione = :id_pren AND ResponsabileEmail = :resp_id";
    
    $stmtUpdate = $cid->prepare($sqlUpdate);
    $stmtUpdate->execute([
        ':sala' => $sala,
        ':data_pren' => $data_pren,
        ':ora_inizio' => $ora_inizio,
        ':durata' => $durata,
        ':attivita' => $attivita,
        ':id_pren' => $id_prenotazione,
        ':resp_id' => $responsabile_email
    ]);

    echo "<p>Modifica avvenuta con successo</p>";
}

function deleteReservation($cid, $data) {
    $id_pren = $data['IDPrenotazione'];
    // $responsabile_email = $_SESSION['ResponsabileEmail'];

    $sqlDelete = "DELETE FROM Prenotazione WHERE IDPrenotazione = :id AND ResponsabileEmail = :resp_id";
    
    $stmtDelete = $cid->prepare($sqlDelete);
    $stmtDelete->execute([
        ':id' => $id_pren,
        ':resp_id' => $responsabile_email
    ]);

    if ($stmtDelete->rowCount() > 0) {
        echo "<p>Prenotazione eliminata</p>";
    } else {
        echo "<p>Eliminazione NON avvenuta</p>";
    }
}
?>