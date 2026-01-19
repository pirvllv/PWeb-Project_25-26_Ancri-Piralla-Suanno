<?php

session_start();

/* Controllo validita' utente */
if(!isset($_SESSION) || $_SESSION['logged_in'] == false /*|| $_SESSION['root'] == false*/) {
    http_response_code(403);
    echo "Error 403: forbidden";
    exit;
}

require_once "../backend/connection.php";

header('Content-Type: application/json');

$cid = connessione($hostname, $username, $password, $dbname);
$responsabile_email = $_SESSION['user'];

if (!$cid) {
    echo json_encode(['success' => false, 'message' => 'Connessione al database non riuscita']);
    exit;
}

$action = isset($_POST['azione']) ? $_POST['azione'] : (isset($_GET['azione']) ? $_GET['azione'] : '');

switch ($action) {
    case 'accettaIscrizione':
        assegnaRuolo($cid, $_POST);
        break;
    
    case 'nominaResponsabile':
        nominaResponsabile($cid, $_POST);
        break;

    case 'mostraSettori':
        mostraSettori($cid);
        break;
}

function assegnaRuolo($cid, $data) {
    $email = $data['email'];
    $ruolo = $data['ruolo'];

    $sqlCheck = "SELECT Ruolo
                 FROM Iscritto
                 WHERE Email = ?";
    $stmtCheck = $cid->prepare($sqlCheck);
    $stmtCheck->bind_param("s", $email);
    $stmtCheck->execute();
    $resCheck = $stmtCheck->get_result();

    if ($resCheck['Ruolo'] === $ruolo) {
        echo json_encode(['success' => false, 'message' => 'Ruolo già assegnato']);
        return;
    }
    
    $sqlAssegna =  "UPDATE Iscritto
                    WHERE Email = ?
                    SET Ruolo = ?";
    $stmtAssegna = $cid->prepare($sqlAssegna);
    $stmtAssegna->bind_param("ss", $email, $ruolo);

    if ($stmtAssegna->execute()) {
        echo json_encode(['success' => true, 'message' => 'Ruolo modificato']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Errore nell\'assegnazione']);
    }
    return;
}

function nominaResponsabile($cid, $data) {
    $email_resp = $data['email_resp'];
    $corso = $data['corso'];
    
    $sqlCheck = "SELECT COUNT(*) as giaResp
                 FROM Settore
                 WHERE ResponsabileEmail = ?";
    
    $stmtCheck = $cid->prepare($sqlCheck);
    $stmtCheck->bind_param("s", $email_resp);
    $stmtCheck->execute();
    $resCheck = $stmtCheck->get_result()->fetch_assoc();

    if($resCheck['giaResp'] > 0) {
        echo json_encode(['success' => false, 'message' => 'Utente già responsabile']);
        return;
    }

    $sqlUpdate = "UPDATE Settore
                  SET ResponsabileEmail = ?
                  WHERE Nome = ?";
    $stmtUpdate = $cid->prepare($sqlUpdate);
    $stmtUpdate->bind_param("ss", $email_resp, $corso);

    if($stmtUpdate->execute()) {
        echo json_encode(['success' => true, 'message' => 'Nuovo responsabile assegnato']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Errore nell\'assegnamento del responsabile']);
    }

    return;
}

function mostraSettori($cid) {
    $sql = "SELECT 
                Settore.Nome AS SettoreNome,
                Settore.ResponsabileEmail AS ResponsabileEmail,
                Iscritto.Nome AS ResponsabileNome,
                Iscritto.Cognome AS ResponsabileCognome
            FROM Settore
            LEFT JOIN Iscritto
                ON Iscritto.Email = Settore.ResponsabileEmail;
";
    
    $stmt = $cid->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $settori = [];
    while ($row = $result->fetch_assoc()) {
        $settori[] = $row;
    }

    echo json_encode(['success' => true, 'data' => $settori]);

    return;
}

?>