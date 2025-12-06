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
    $data = $_POST['datiPren'];
    switch ($_POST['selPrenot']) {
            case 'create':
                createReservation($cid, $input);
                break;
            case 'update':
                updateReservation($cid, $input);
                break;
            case 'delete':
                deleteReservation($cid, $input);
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
        exit;
    }

    // output HTML diretto
    while ($row = $result->fetch_assoc()) {

        // ogni prenotazione genera un DIV cliccabile
        echo "
        <div class='pren-item' 
             data-id='{$row['IDPrenotazione']}'
             data-data='{$row['DataPren']}'
             data-ora-inizio='{$row['OraInizio']}'
             data-ora-fine='{$row['OraFine']}'
             data-aula='{$row['NumAula']}'
             data-attivita='{$row['Attivita']}'
             style='padding:8px; border-bottom:1px solid #ddd; cursor:pointer;'>
             
             {$row['IDPrenotazione']} | {$row['DataPren']} {$row['OraInizio']}-{$row['OraFine']} | Aula {$row['NumAula']}
        </div>
        ";
    }

    exit;
}

function createReservation($cid, $data) {
    $sala = $data['NumAula'];
    $responsabile_email = $_SESSION['ResponsabileEmail'];
    $data_pren = $data['DataPren'];
    $ora_inizio = (int)$data['OraInizio'];
    $ora_fine = (int)$data['OraFine'];
    $attivita = $data['Attivita'];

    $durata = $ora_fine - $ora_inizio;

    if ($ora_inizio < 9 || $ora_fine > 23 || $durata <= 0) {
        echo "<script>alert('Orario non valido');</script>";
    }

    $stmt = "SELECT MAX(IDPrenotazione) AS max_id FROM Prenotazione";
    $res = $cid->query($stmt);
    $row = $res->fetch_assoc();
    $id_max = $row['max_id'];

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
        ':sala' => $sala,
        ':data_pren' => $data_pren,
        ':ora_inizio' => $ora_inizio,
        ':ora_fine' => $ora_fine,
    ]);
    
    if ($stmtCheck->fetch()['occupata'] > 0) {
        echo "<script>alert('Sala già occupata');</script>";
        return;
    }

    

    $sqlInsert = "INSERT INTO Prenotazione (IDPrenotazione, DataPren, OraInizio, OraFine, Attivita, NumAula, ResponsabileEmail) 
                  VALUES (:id_pren, :data_pren, :ora_inizio, :ora_fine, :attivita, :sala, :responsabile_email)";
    
    $stmtInsert = $cid->prepare($sqlInsert);
    $stmtInsert->execute([
        ':sala' => $sala,
        ':id_pren' => $id_max +1,
        ':resp_id' => $responsabile_email,
        ':data_pren' => $data_pren,
        ':ora_inizio' => $ora_inizio,
        ':durata' => $durata,
        ':attivita' => $attivita
    ]);

    echo "<script>alert('Prenotazione avvenuta con successo');</script>";
}

function updateReservation($cid, $data) {
    $id_prenotazione = $data['IDPrenotazione'];
    $sala = $data['NumAula'];
    $responsabile_email = $_SESSION['ResponsabileEmail'];
    $data_pren = $data['DataPren'];
    $ora_inizio = (int)$data['OraInizio'];
    $ora_fine = (int)$data['OraFine'];
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
    $responsabile_email = $_SESSION['ResponsabileEmail'];

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