<?php
if (!isset($_SESSION['ResponsabileEmail'])) {
    echo "<script>alert('Accesso negato');</script>";
    return;
}

if (isset($_POST['selPrenot'])){
    $data = $_POST['datiPren'];
    switch ($_POST) {
            case 'create':
                createReservation($pdo, $input);
                break;
            case 'update':
                updateReservation($pdo, $input);
                break;
            case 'delete':
                deleteReservation($pdo, $input);
                break;
            default:
                echo "<script>alert('Azione non valida');</script>";
    }
}

function createReservation($pdo, $data) {
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
    $res = $pdo->query($stmt);
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

    
    $stmtCheck = $pdo->prepare($sqlCheck);
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

    

    $sqlInsert = "INSERT INTO Prenotazioni (IDPrenotazione, DataPren, OraInizio, OraFine, Attivita, NumAula, ResponsabileEmail) 
                  VALUES (:id_pren, :data_pren, :ora_inizio, :ora_fine, :attivita, :sala, :responsabile_email)";
    
    $stmtInsert = $pdo->prepare($sqlInsert);
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

function updateReservation($pdo, $data) {
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
                 
    $stmtCheck = $pdo->prepare($sqlCheck);
    $stmtCheck->execute([
        ':sala' => $sala,
        ':data_pren' => $data_pren,
        ':ora_inizio' => $ora_inizio,
        ':ora_fine' => $ora_fine
    ]);

    if ($stmtCheck->fetch()['occupata'] > 0) {
        echo "<p>Sala già occupata</p>";
        return;
    }

    $sqlUpdate = "UPDATE prenotazioni 
                  SET sala_id = :sala, 
                      data_prenotazione = :data_pren, 
                      ora_inizio = :ora_inizio, 
                      durata = :durata, 
                      attivita = :attivita 
                  WHERE id = :id_pren AND responsabile_email = :resp_id";
    
    $stmtUpdate = $pdo->prepare($sqlUpdate);
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

function deleteReservation($pdo, $data) {
    $id_prenotazione = $data['IDPrenotazione'];
    $responsabile_email = $_SESSION['ResponsabileEmail'];

    $sqlDelete = "DELETE FROM prenotazioni WHERE id = :id AND ResponsabileEmail = :resp_id";
    
    $stmtDelete = $pdo->prepare($sqlDelete);
    $stmtDelete->execute([
        ':id' => $id_prenotazione,
        ':resp_id' => $responsabile_email
    ]);

    if ($stmtDelete->rowCount() > 0) {
        echo "<p>Prenotazione eliminata</p>";
    } else {
        echo "<p>Eliminazione NON avvenuta</p>";
    }
}
?>