<?php
function connessione($hostname, $username, $password, $dbname) {
    $cid = new mysqli($hostname, $username, $password, $dbname);
    if ($cid->connect_error) {
        die("Errore di connessione al db $dbname: " . $cid->connect_error);
    }
    return $cid;
}
?>