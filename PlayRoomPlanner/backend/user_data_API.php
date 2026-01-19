<?php
require_once "../backend/connection.php";
require_once "../common/functions.php";

$cid = connessione($hostname, $username, $password, $dbname);
if(!$cid) {fail("Errore di connessione al database. Contatta un tecnico");}

$azione = "";
$primkey = "";
if(esiste("action", $_POST)=="") {
    fail("Azione mancante per completare la query. Contatta un tecnico");
} else {
    $azione = $_POST["action"];
}

if ($azione != "inserisci" && $azione != "aggiorna") {
    if(esiste("primkey", $_POST)=="") {
    fail("Chiave mancante per completare la query. Contatta un tecnico");
    } else {
        $primkey = $_POST["primkey"];
    }
}

$query = "";
//$outMsg = "";
if ($azione=="") {
    $cid->close();
    fail("Non c'è azione legata ai dati. Contatta un tecnico");
    
} else if ($azione=="aggiorna") {
        $err = update_session();
        if ($err!=""){
            fail("Aggiornamento dati - ".$err);
        } else {
            success("Sessione aggiornata");
            exit();
        }
} else if ($azione=="elimina") {
        $query = delete_query("Iscritto", $primkey, "Email");
} else if ($azione=="getData") {
        $query = "SELECT Email, Nome, Cognome, Password, Foto, Ruolo, DataNascita
                    FROM Iscritto ";
        $query .= "WHERE Email = \"".$primkey."\"";
} else {

    $dati = array();
    if(isset($_POST["email"])) {$dati["Email"] = $_POST["email"];}
    if(isset($_POST["name"])) {$dati["Nome"] = $_POST["name"];}
    if(isset($_POST["surname"])) {$dati["Cognome"] = $_POST["surname"];}
    // in caso di modifica della password per utente esistente o di inserimento nuovo utente, critta la password dal form e la inserisce nel db
    if(isset($_POST["password"])) {$dati["Password"] = password_hash($_POST["password"], PASSWORD_DEFAULT);}
    if(isset($_POST["role"])) {$dati["Ruolo"] = $_POST["role"];}
    if(isset($_POST["DOB"])) {$dati["DataNascita"] = $_POST["DOB"];}

    $fotonome = esiste("photoname", $_POST);
    if(isset($_FILES["photo"]) && $fotonome != "") {

        $destination = '../immagini/foto_profilo/'.$fotonome;

        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
            fail("Attenzione! Errore nel caricamento della nuova foto.");
        } else {
            //success("fooooooo");
        }
    }

    if ($azione=="inserisci") {
        $query = insert_query($dati, "Iscritto");
    } else if ($azione=="modifica") {
        $query = update_query($dati, "Iscritto", $_POST["primkey"], "Email");
    }
    
}

if ($query == -1) {

    if (!isset($_FILES["photo"])) {
        $cid->close();
        fail("Cambia almeno un campo");
    } else {

        success("Foto aggiornata con successo");

    }

}

try { 
    $result = $cid->query($query);
    //$outMsg =  "Query eseguita correttamente";
} catch (mysqli_sql_exception $e) {
    //fail($e->getMessage());
    //fail($e->getCode());
    switch ($e->getCode()) {

        case 1062:
        case 1761: // Duplicate entry
            $errorMessage = "La mail inserita esiste già.";
            break;
    
        default:
            $errorMessage = "Errore nell'esecuzione della richiesta al database. Contatta un tecnico";
            fail($query);
            break;
    }
    fail($errorMessage);
}
$cid->close();

if ($azione == "getData") {
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $dati = array("email" => $row["Email"], 
                  "name" => $row["Nome"], 
                  "surname" => $row["Cognome"], 
                  //"pwd" => $row["Password"], 
                  "photo" => $row["Foto"], 
                  "role" => $row["Ruolo"], 
                  "DOB" => $row["DataNascita"]);
        }

        echo json_encode(["success" => true, "dati" => $dati]);
    } 
} else if ($azione == "modifica") {
    success("Modifiche eseguite con successo");
} else if ($azione == "elimina") {
    success("Account eliminato con successo");
} else if ($azione == "inserisci") {
    success("Account creato con successo");
}

?>
