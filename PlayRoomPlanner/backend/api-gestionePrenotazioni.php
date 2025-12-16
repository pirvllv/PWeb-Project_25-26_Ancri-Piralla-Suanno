<?php

session_start();

if(!isset($_SESSION) || $_SESSION['logged_in'] == false || $_SESSION['responsabile'] == false) {
    http_response_code(403);
    echo "Error 403: forbidden";
    exit;
}

require_once "../backend/connection.php";

header('Content-Type: application/json');

$cid = connessione($hostname, $username, $password, $dbname);
$responsabile_email = "anna.verdi@email.com";

if (!$cid) {
    echo json_encode(['success' => false, 'message' => 'Connessione al database non riuscita']);
    exit;
}

$action = isset($_POST['azione']) ? $_POST['azione'] : (isset($_GET['azione']) ? $_GET['azione'] : '');

switch ($action) {
    case 'crea':
        creaPrenotazione($cid, $_POST);
        break;

    case 'modifica':
        modificaPrenotazione($cid, $_POST);
        break;

    case 'elimina':
        eliminaPrenotazione($cid, $_POST);
        break;

    case 'mostraPren':
        mostraPrenotazioni($cid);
        break;

    case 'getAule':
        getAule($cid);
        break;

    case 'getInviti':
        getInviti($cid, $_POST);
        break;
    
    case 'invita':
        invitaUtenti($cid, $_POST);
        break;

    case 'checkValidEmail':
        checkValidEmail($cid, $_POST);
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Azione non valida']);
}

function mostraPrenotazioni($cid) {
    global $responsabile_email;
    
    $sql = "SELECT IDPrenotazione, DataPren, OraInizio, OraFine, NumAula, Attivita
            FROM Prenotazione
            WHERE ResponsabileEmail = ?
            ORDER BY DataPren, OraInizio";

    $stmt = $cid->prepare($sql);
    $stmt->bind_param("s", $responsabile_email);
    $stmt->execute();
    $result = $stmt->get_result();

    $prenotazioni = [];
    while ($row = $result->fetch_assoc()) {
        $prenotazioni[] = $row;
    }

    echo json_encode(['success' => true, 'data' => $prenotazioni]);
}

function creaPrenotazione($cid, $data) {
    global $responsabile_email;
    
    $sala = $data['NumAula'];
    $data_pren = $data['DataPren'];
    $attivita = $data['Attivita'];
    $ora_inizio_str = $data['OraInizio'];
    $ora_fine_str = $data['OraFine'];

    $ora_inizio = strtotime($ora_inizio_str);
    $ora_fine = strtotime($ora_fine_str);

    $limite_start = strtotime("09:00");
    $limite_end = strtotime("23:00");

    if ($ora_inizio < $limite_start || $ora_fine > $limite_end || $ora_fine <= $ora_inizio) {
        echo json_encode(['success' => false, 'message' => 'Orario non valido']);
        return;
    }

    $sqlCheck = "SELECT COUNT(*) AS occupata
                 FROM Prenotazione
                 WHERE NumAula = ?
                 AND DataPren = ?
                 AND NOT (OraFine <= ? OR OraInizio >= ?)";

    $stmtCheck = $cid->prepare($sqlCheck);
    $stmtCheck->bind_param("ssss", $sala, $data_pren, $ora_inizio_str, $ora_fine_str);
    $stmtCheck->execute();
    $resCheck = $stmtCheck->get_result()->fetch_assoc();

    if ($resCheck['occupata'] > 0) {
        echo json_encode(['success' => false, 'message' => 'Sala già occupata']);
        return;
    }

    $sqlInsert = "INSERT INTO Prenotazione (DataPren, OraInizio, OraFine, Attivita, NumAula, ResponsabileEmail)
                  VALUES (?, ?, ?, ?, ?, ?)";

    $stmtInsert = $cid->prepare($sqlInsert);
    $stmtInsert->bind_param("ssssss", $data_pren, $ora_inizio_str, $ora_fine_str, $attivita, $sala, $responsabile_email);

    if ($stmtInsert->execute()) {
        echo json_encode(['success' => true, 'message' => 'Prenotazione creata con successo']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Errore nella creazione']);
    }
}

function modificaPrenotazione($cid, $data) {
    global $responsabile_email;
    
    $id_prenotazione = $data['IDPrenotazione'];
    $sala = $data['NumAula'];
    $data_pren = $data['DataPren'];
    $ora_inizio_str = $data['OraInizio'];
    $ora_fine_str = $data['OraFine'];
    $attivita = $data['Attivita'];

    $ora_inizio = strtotime($ora_inizio_str);
    $ora_fine = strtotime($ora_fine_str);

    if ($ora_inizio >= $ora_fine) {
        echo json_encode(['success' => false, 'message' => 'Orario non valido']);
        return;
    }

    $sqlCheck = "SELECT COUNT(*) as occupata 
                 FROM Prenotazione
                 WHERE NumAula = ? 
                 AND DataPren = ?
                 AND IDPrenotazione != ?
                 AND NOT (OraFine <= ? OR OraInizio >= ?)";

    $stmtCheck = $cid->prepare($sqlCheck);
    $stmtCheck->bind_param("ssiss", $sala, $data_pren, $id_prenotazione, $ora_inizio_str, $ora_fine_str);
    $stmtCheck->execute();
    $resCheck = $stmtCheck->get_result()->fetch_assoc();

    if ($resCheck['occupata'] > 0) {
        echo json_encode(['success' => false, 'message' => 'Sala già occupata']);
        return;
    }

    $sqlUpdate = "UPDATE Prenotazione 
                  SET NumAula = ?, DataPren = ?, OraInizio = ?, OraFine = ?, Attivita = ?
                  WHERE IDPrenotazione = ? AND ResponsabileEmail = ?";

    $stmtUpdate = $cid->prepare($sqlUpdate);
    $stmtUpdate->bind_param("sssssss", $sala, $data_pren, $ora_inizio_str, $ora_fine_str, $attivita, $id_prenotazione, $responsabile_email);

    if ($stmtUpdate->execute()) {
        echo json_encode(['success' => true, 'message' => 'Prenotazione modificata']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Errore nella modifica']);
    }
}

function eliminaPrenotazione($cid, $data) {
    global $responsabile_email;
    
    $id_pren = $data['IDPrenotazione'];

    $sqlDelete = "DELETE FROM Prenotazione WHERE IDPrenotazione = ? AND ResponsabileEmail = ?";

    $stmtDelete = $cid->prepare($sqlDelete);
    $stmtDelete->bind_param("is", $id_pren, $responsabile_email);
    $stmtDelete->execute();

    if ($stmtDelete->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Prenotazione eliminata']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Eliminazione fallita']);
    }
}

function getAule($cid) {
    global $responsabile_email;
    
    $sql = "SELECT DISTINCT sp.NumAula, sp.Capienza, sp.SettoreNome
            FROM SalaProve sp
            INNER JOIN Settore s ON sp.SettoreNome = s.Nome
            WHERE s.Tipologia = (
                SELECT DISTINCT s2.Tipologia
                FROM Settore s2
                WHERE s2.ResponsabileEmail = ?
                LIMIT 1
            )
            ORDER BY sp.NumAula";

    $stmt = $cid->prepare($sql);
    $stmt->bind_param("s", $responsabile_email);
    $stmt->execute();
    $result = $stmt->get_result();

    $aule = [];
    while ($row = $result->fetch_assoc()) {
        $aule[] = $row;
    }

    echo json_encode(['success' => true, 'data' => $aule]);
}

function checkValidEmail($cid, $data) {
    $emailInvitato = $data['emailInvitato'];

    $sql = "SELECT COUNT(*) AS conteggio
            FROM Iscritto
            WHERE Iscritto.Email = ?";

    $stmt = $cid->prepare($sql);
    $stmt->bind_param("s", $emailInvitato);
    $stmt->execute();
    $resCheck = $stmt->get_result()->fetch_assoc();

    if ($resCheck['conteggio'] > 0) {
        echo json_encode(['success' => true, 'message' => 'Utente iscritto']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Utente non iscritto']);
    }
}

function getInviti($cid, $data) {
    $id_prenotazione = $data['id'];
    $inviti = getInvitiPHP($cid, $id_prenotazione);

    echo json_encode([
        'success' => true,
        'inviti' => $inviti
    ]);
}

function getInvitiPHP($cid, $id_prenotazione) {

    $sql = "SELECT IscrittoEmail
            FROM Invito
            WHERE IDPrenotazione = ?";

    $stmt = $cid->prepare($sql);
    $stmt->bind_param("i", $id_prenotazione);
    $stmt->execute();
    $result = $stmt->get_result();

    $inviti = [];
    while ($row = $result->fetch_assoc()) {
        $inviti[] = $row['IscrittoEmail'];
    }
    return $inviti;
}

function invitaUtenti ($cid, $data) {
    $inviti = json_decode($data['inviti'], true);
    $id_pren = $data['IDPren'];

    $giaInvitati = getInvitiPHP($cid, $id_pren);
    $nuoviInviti = [];

    foreach ($inviti as $email) {
        if (!in_array($email, $giaInvitati)) {
            $nuoviInviti[] = $email;
        }
    }

    if (empty($nuoviInviti)) {
        echo json_encode([
            'success' => false,
            'message' => 'Nessun nuovo invito da inviare'
        ]);
        return;
    }

    foreach ($nuoviInviti as $email) {
        $sqlInsert = "INSERT INTO Invito (IDPrenotazione, IscrittoEmail, Accettazione, Motivazione, DataRisposta)
                      VALUES (?, ?, 0, NULL, NULL)";
        $stmtInsert = $cid->prepare($sqlInsert);
        $stmtInsert->bind_param("is", $id_pren, $email);

        if (!$stmtInsert->execute()) {
            echo json_encode([
                'success' => false,
                'message' => 'Errore durante l\'invio degli inviti'
            ]);
            return;
        }
    }

    echo json_encode([
        'success' => true,
        'message' => 'Inviti inviati con successo'
    ]);
}
?>