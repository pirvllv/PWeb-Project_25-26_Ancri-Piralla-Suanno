<?php
require_once "../backend/connection.php";
require_once "../common/functions.php";

session_start();
/* Controllo validita' utente */
if(!isset($_SESSION) || $_SESSION['logged_in'] == false) {
    http_response_code(403);
    fail("Error 403: forbidden");
    exit;
}

$cid = connessione($hostname, $username, $password, $dbname);
if(!$cid) {fail("Errore di connessione al database. Contatta un tecnico");}

$azione = "";
$primkey = "";
if(esiste("action", $_POST)=="") {
    fail("Azione mancante per completare la query. Contatta un tecnico");
} else {
    $azione = trim($_POST["action"]);
}

if ($azione != "inserisci" && $azione != "aggiorna") {
    if(esiste("primkey", $_POST)!="") {
        $primkey = trim($_POST["primkey"]);
    } else {
        fail("Chiave mancante per completare la query. Contatta un tecnico");
    }
}

if($_SESSION['user'] != $primkey && !$_SESSION["admin"]) {
    http_response_code(403);
    fail("Error 403: forbidden access");
    exit;
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
} else if ($azione=="inserisci" || $azione=="modifica"){

    $dati = array();
    if(isset($_POST["email"])) {$dati["Email"] = trim($_POST["email"]);}
    if(isset($_POST["name"])) {$dati["Nome"] = trim($_POST["name"]);}
    if(isset($_POST["surname"])) {$dati["Cognome"] = trim($_POST["surname"]);}
    // in caso di modifica della password per utente esistente o di inserimento nuovo utente, critta la password dal form e la inserisce nel db
    if(isset($_POST["password"])) {$dati["Password"] = password_hash(trim($_POST["password"]), PASSWORD_DEFAULT);}
    if(isset($_POST["DOB"])) {$dati["DataNascita"] = trim($_POST["DOB"]);}

    if(isset($_POST["role"])) {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if ($_SESSION["admin"]){
            $dati["Ruolo"] = trim($_POST["role"]);
        }
    }

    $check = checkDati($dati);
    if(!$check["ok"]) {fail($check["msg"]);}

    if($azione=="inserisci") {
        $fotonome = getFotoNome($dati["Nome"], $dati["Cognome"]);
    } else if ($azione=="modifica"){
        $fotonome = trim(esiste("photoname", $_POST));
        if ($fotonome=="") {
            fail("Nome file della foto mancante. Contatta un tecnico");
        }
    }
    
    if(isset($_FILES["photo"]) && $fotonome != "") {

        $destination = '../immagini/foto_profilo/'.$fotonome;
        $dati["Foto"] = $fotonome;

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
    
} else {
    fail("Azione sbagliata nella chiamata a user API. Contatta un tecnico");
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

function checkDati($data) {

    /*
    --mail formato corretto
    --pass con caratteri: lettere, numeri, @, *, $
    --Nome e cognome solo lettere, 1 <lunghezza < 100
    --DOB esistente e passata
    --Ruolo modificabile solo se root
    Ruolo tra quelli consentiti
    */

    $okk = true;
    $mess = "";
    $errcount = 0;

    // Check pass
    if (isset($data["Password"])) {

        $pw = $data["Password"];

        if (strlen($pw) < 3 || strlen($pw) > 255) {
            $okk = false;
            $errcount++;
            $mess .= "La lunghezza della password deve essere tra 3 e 255 caratteri\n";
        }

        if (!preg_match('/^[a-zA-Z0-9@-_]+$/', $pw)) {
            $okk = false;
            $errcount++;
            $mess .= "La password può contenere solo lettere ASCII, numeri, @, -, _\n";
        }
    }

    // Check mail
    if (isset($data["Email"])) {

        $em = $data["Email"];

        if (!preg_match('/^[a-zA-Z][\w]*\.[\w]+@[\w]+\.[a-zA-Z]+$/', $em)) {
            $okk = false;
            $errcount++;
            $mess .= "L'email non è nel formato corretto o ha caratteri vietati\n";
        }
    }

    // Check DOB
    if (isset($data["DataNascita"])) {

        try {
            $date = new DateTime($data["DataNascita"]);
            $today = new DateTime();
            $today->setTime(0, 0, 0);

            if ($date >= $today) {
                $okk = false;
                $errcount++;
                $mess .= "Data di nascita deve essere nel passato\n";
            }

            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data["DataNascita"])) {
                $okk = false;
                $errcount++;
                $mess .= "Data di nascita nel formato sbagliato\n";
            }
        } catch (Exception $e) {
            $okk = false;
            $errcount++;
            $mess .= "Data di nascita non valida\n";
        }
    }

    // Check nome
    if (isset($data["Nome"])) {

        $nomi = preg_split('/\s+/', trim($data["Nome"]));

        foreach ($nomi as $nm) {

            if (!preg_match('/^\p{L}+$/u', $nm)) {
                $okk = false;
                $errcount++;
                $mess .= "Il nome può contenere solo lettere\n";
                break;
            }

            if (strlen($nm) > 100) {
                $okk = false;
                $errcount++;
                $mess .= "Il nome deve avere massimo 100 lettere\n";
                break;
            }

            if (strlen($nm) < 1) {
                $okk = false;
                $errcount++;
                $mess .= "Il nome deve avere almeno una lettera\n";
                break;
            }
        }

        $data["Nome"] = implode(" ", $nomi);
    }

    // Check cognome
    if (isset($data["Cognome"])) {

        $cognomi = preg_split('/\s+/', trim($data["Cognome"]));

        foreach ($cognomi as $cnm) {

            if (!preg_match('/^\p{L}+$/u', $cnm)) {
                $okk = false;
                $errcount++;
                $mess .= "Il cognome può contenere solo lettere\n";
                break;
            }

            if (strlen($cnm) > 100) {
                $okk = false;
                $errcount++;
                $mess .= "Il cognome deve avere massimo 100 lettere\n";
                break;
            }

            if (strlen($cnm) < 1) {
                $okk = false;
                $errcount++;
                $mess .= "Il cognome deve avere almeno una lettera\n";
                break;
            }
        }

        $data["Cognome"] = implode(" ", $cognomi);
    }

    // Check ruolo
    if (isset($data["Ruolo"])) {

        $rl = $data["Ruolo"];
        $allowedRoles = ["studente", "tecnico", "docente"];

        if (!in_array($rl, $allowedRoles)) {
            $okk = false;
            $errcount++;
            $mess .= "Ruolo non consentito\n";
        }
    }

    $mess = "Ci sono " . $errcount . " errori nei dati:\n" . $mess;

    return ["ok" => $okk, "msg" => $mess];
}


?>
