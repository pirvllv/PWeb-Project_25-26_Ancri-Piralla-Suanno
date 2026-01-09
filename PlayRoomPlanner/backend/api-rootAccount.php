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
    
}

function accettaIscrizione($cid, $data) {
    $email = $data['email'];
    $corso = $data['corso'];

}



function nominaResponsabile($cid, $data) {
    $email_resp = $data['email_resp'];
    $corso = $data['corso'];

}

?>