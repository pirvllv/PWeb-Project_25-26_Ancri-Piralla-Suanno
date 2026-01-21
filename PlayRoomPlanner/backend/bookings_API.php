<?php

require_once("../common/functions.php");
require_once("../backend/connection.php");

session_start();

/* Controllo validita' utente */
if(!isset($_SESSION) || $_SESSION['logged_in'] == false) {
    http_response_code(403);
    fail("Error 403: forbidden");
    exit;
}

$cid = connessione($hostname, $username, $password, $dbname);
//$qry = "";

if (!$cid) { fail("Connessione al database non riuscita. Contatta un tecnico"); }

//print_r($_GET);
$primkey = mysqli_real_escape_string($cid,esiste("primkey", $_GET));
$today = esiste("today", $_GET);
$type = esiste("type", $_GET);
if (($today=="" && $type!="change") || $primkey=="" || $type=="") {
    fail('Non ci sono abbastanza dati per la chiamata API (week/invites). Contatta un tecnico');
}

if($_SESSION['user'] != $primkey) {
    http_response_code(403);
    fail("Error 403: forbidden access");
    exit;
}


if(!in_array($type, array("week", "invites", "change", "room"))) {fail("Tipo incorretto di chiamata API. Contatta un tecnico");}

$dati = array();
$hmax = 0;
if($type!="change") {
    $mondayStamp = getMondayStamp($today);
    $weekdays = getWeekdays();

    $bookings = get_bookings($primkey, $mondayStamp, $type);
    

    if ($type!="invites") {
        $week = array();
        
        for ($g = 0; $g < count($weekdays); $g++) {
            
            $week[$g] = array($weekdays[$g], date("j".($type=="invites"?"/m":""), strtotime("+".$g." days", $mondayStamp)));
            
        }
        $dati["week"] = $week;
        $dati["weekstart"] = date("d/m/Y", $mondayStamp);
        $dati["hmax"] = $hmax;
    }

    $dati["bookings"] = $bookings;
    echo json_encode(["success" => true, "dati" => $dati]);
} else {
    //print_r($_GET);
    $idp = esiste("IDP", $_GET);;
    $action = esiste("action", $_GET);
    $just = mysqli_real_escape_string($cid,esiste("just", $_GET));
    
    if ($action=="" || $idp=="") {
        fail('Non ci sono abbastanza dati per la chiamata API (change). Contatta un tecnico');
    }

    if($action!=0 && $action!=1) {
        fail("Codice incorretto di azione inviti. Contatta un tecnico");
    }

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $user = $_SESSION["user"];

    if ($action == 1) {
        
        $just = NULL;

        //Controllo della non sovrapposizione di un'invito che si sta accettando con altre attività
        $sqlIF = "SELECT OraInizio, OraFine
                    FROM Prenotazione
                    WHERE IDPrenotazione = ?";

        $sqlCheck = "SELECT COUNT(*) AS conflitto
                    FROM Prenotazione INNER JOIN Invito
                    ON (Prenotazione.IDPrenotazione = Invito.IDPrenotazione)
                    WHERE Invito.IscrittoEmail = ?
                    AND Invito.Accettazione = 1
                    AND DataPren = (SELECT DataPren
                                    FROM Prenotazione 
                                    WHERE IDPrenotazione = ?)
                    AND NOT (OraFine <= ? OR OraInizio >= ?);";

        try {
            $stmtIF = $cid->prepare($sqlIF);
            $stmtIF->bind_param("i", $idp);
            $stmtIF->execute();
            $stmtIF = $stmtIF->get_result();
            
            if ($stmtIF->num_rows == 0) {
                fail("IDPrenotazione non trovato. Contatta un tecnico");
            } else if ($stmtIF->num_rows > 1) {
                fail("IDPrenotazione duplicato. Contatta un tecnico");
            }
            $resIF = $stmtIF->fetch_assoc();
            

            $stmtCheck = $cid->prepare($sqlCheck);
            $stmtCheck->bind_param("siss", $user, $idp, $resIF["OraInizio"], $resIF["OraFine"]);
            $stmtCheck->execute();
            $resCheck = $stmtCheck->get_result()->fetch_assoc();
            
        } catch (exception $e) {
            $errorMessage = $e->getMessage();
            fail("Contatta un tecnico: ".$errorMessage);
        }

        if ($resCheck['conflitto'] > 0) {
            fail("Questo invito è in conflitto con un'attività già in programma");
        }
    } else {
        if ($just =="") {
            fail("Nessuna giustificazione nella chiamata API. Contatta un tecnico");
        }

        
    }

    //Modifica del campo "accettazione"
    $sqlChange = "UPDATE Invito
                    SET Accettazione = ?, Motivazione = ?, DataRisposta = ?
                    WHERE IDPrenotazione = ?
                    AND IscrittoEmail = ?";

    try {
        $stmtChange = $cid->prepare($sqlChange);
        $td = date("Y-m-d H:i:s", time());
        $stmtChange->bind_param("issis", $action, $just, $td,$idp, $user);
        $stmtChange->execute();
        $stmtChange->get_result();
    } catch (exception $e) {
        $errorMessage = $e->getMessage();
        fail("Contatta un tecnico: ".$errorMessage);
    }

    success("Invito ".($action==1?"accettato":"rifiutato")." con successo");

}

//Funzioni

function getMondayStamp($stamp) {

    return strtotime("+1 Day", strtotime("Last Sunday", $stamp));
    
}

function creaAtt(string $att, $data, string $stato, int $secinizio, float $secfine, int $id) {

    //secinizio e secfine sono timestamp
    return array("attivita"=>$att, "data" => $data, "stato"=>$stato, "orainizio"=>$secinizio, "orafine"=>$secfine, "IDP" => $id);

}

function user_invites_query(string $email, int $data1, int $data2) {

    $lunedi = date("Y-m-d", $data1);
    $domenica = date("Y-m-d", $data2);
    $query = "SELECT Invito.IDPrenotazione, Attivita, DataPren, OraInizio, OraFine, Accettazione
            FROM Prenotazione INNER JOIN Invito
            ON (Prenotazione.IDPrenotazione = Invito.IDPrenotazione";
    $query .= " AND \"".$lunedi."\" <= Prenotazione.DataPren";
    if ($data2 != -1) {
        $query .= " AND Prenotazione.DataPren <= \"".$domenica."\"";
    }
    $query .= " AND Invito.IscrittoEmail=\"".$email."\"";
    $query .= " AND (Invito.Accettazione ".($data2==-1?"IS NULL OR Invito.Accettazione=0":"= 1").")";
    $query .= ")";
    $query .= " ORDER BY DataPren, OraInizio";

    return $query;
    
}

function room_bookings_query(string $room, int $data) {

    $lunedi = date("Y-m-d", $data);
    $domenica = date("Y-m-d", strtotime("+6 days", $data));
    $query = "SELECT IDPrenotazione, Attivita, DataPren, OraInizio, OraFine
            FROM Prenotazione";
    $query .= " WHERE (";
    $query .= "\"".$lunedi."\" <= DataPren";
    $query .= " AND DataPren <= \"".$domenica."\"";
    $query .= " AND NumAula=\"".$room."\"";
    $query .= ")";
    $query .= " ORDER BY DataPren";

    return $query;
    
}

function get_bookings(string $primaryKey, int $data, string $action) {

    global $hostname, $username, $password, $dbname;
    $cid = connessione($hostname, $username, $password, $dbname);
    if(!$cid) {die("Errore di connessione al database. Contatta un tecnico");}

    //$todayStamp = strtotime("10-11-2025");
    $todayStamp = time();
    $query = "";
    switch ($action) {
        case "week": $query = user_invites_query($primaryKey, $data, strtotime("+6 days", $data)); break;
        case "invites": $query = user_invites_query($primaryKey, $todayStamp, -1); break;
        case "room": $query = room_bookings_query($primaryKey, $data); break;
    }
    

    //echo $query;

    try {
        $result = $cid->query($query);
        //echo "Query eseguita correttamente: ".$result;
    } catch (exception $e) {
        $errorMessage = $e->getMessage();
        fail("Contatta un tecnico: ".$errorMessage);
    }
    $cid->close();

    $bookings = array();
    global $hmax;
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            //echo "<p>".print_r($row)."<br></p>";
            //echo "\n\n";
            $dataPrenStamp = strtotime($row["DataPren"]);
            //global $weekdays;
            $dayIdx = intval(date("N", $dataPrenStamp))-1;
            $status = "attNeutra";

            $initAr = explode(":", $row["OraInizio"]);
            $init = $initAr[0]*3600+1800*(int)($initAr[1]/30);

            $endAr = explode(":", $row["OraFine"]);
            $end = $endAr[0]*3600+1800*(int)($endAr[1]/30);

            
            $orari = $initAr[0].":".$initAr[1]."-".$endAr[0].":".$endAr[1];

            if ($action=="invites") {

                switch ($row["Accettazione"]) {
                    case "": $status="attInSospeso"; break;
                    case "0": $status="attRifiutata"; break;
                    //case "1": $status="attAccettata"; break;
                }

                global $weekdays;
                
                $bookings[$dataPrenStamp]["wkday"] = $weekdays[(date("w",$dataPrenStamp)+6)%7]." ".date("d/m",$dataPrenStamp);
                $bookings[$dataPrenStamp]["attivita"][] = creaAtt($row["Attivita"]." (".$orari.")", $dataPrenStamp, $status, $init, $end, $row["IDPrenotazione"]);
            } else {

                if ($end>$hmax) {$hmax = $end;}
                if ($action=="week") {$status = "attAccettata";}
                $bookings[$dayIdx][] = creaAtt("<b>".$row["Attivita"]."</b> <br>".$orari, $dataPrenStamp, $status, $init, $end, $row["IDPrenotazione"]);
        }    
            }
            
            //echo "<p>".$init."<br></p>";
            
            
    }

    return $bookings;

}

?>