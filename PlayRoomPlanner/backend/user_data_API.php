<?php
    require_once("connection.php");
    require_once("../common/functions.php");

    $cid = connessione();
    if(!$cid) {die("Errore di connessione al database");}
    
    $nome = esiste("name", $_POST);
    $cognome = esiste("surname", $_POST);
    $email = esiste("email", $_POST);
    $password = esiste("pwd", $_POST);
    $dataNascita = esiste("DOB", $_POST);
    $ruolo = esiste("role", $_POST);
    $foto = esiste("photo", $_POST);
    $azione = esiste("action", $_POST);

    $query = "";
    if ($azione=="") {
        $cid->close();
        die("Non c'è azione legata ai dati");
        
    } else if ($azione=="elimina") {
        $query = delete_query("Iscritto", $email, "Email");
    } else {

        $dati = array("Email" => $email, 
                      "Nome" => $nome, 
                      "Cognome" => $cognome, 
                      "Password" => $password, 
                      "Foto" => $foto, 
                      "Ruolo" => $ruolo, 
                      "DataNascita" => $dataNascita);

        if ($azione=="inserisci") {
            $query = insert_query($dati, "Iscritto");
        } else if ($azione=="modifica") {
            $query = update_query($dati, "Iscritto", $email, "Email");
        }
        
    }

    if ($query == -1) {
        $cid->close();
        die("Errore nella costruzione della query");
    }
    
    echo "query: ".$query;

    try { 
        $result = $cid->query($query);
        echo "Query eseguita correttamente: ".$result;
    } catch (mysqli_sql_exception $e) {
        $errorMessage = $e->getMessage();
        echo "Errore: ".$errorMessage;
    }
    $cid->close();
?>