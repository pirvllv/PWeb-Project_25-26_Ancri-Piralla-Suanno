<?php

require_once("../common/functions.php");
require_once("../backend/connection.php");

$cid = connessione($hostname, $username, $password, $dbname);
//$qry = "";

if (!$cid) { fail("Connessione al database non riuscita. Contatta un tecnico"); }
//print_r($_GET);
if ((esiste("today", $_GET)=="" && esiste("type", $_GET)!="change") || esiste("primkey", $_GET)=="" || esiste("type", $_GET)=="") {
    fail('Non ci sono abbastanza dati per la chiamata API (week/invites). Contatta un tecnico');
}
if($_GET["type"]!="week" && $_GET["type"]!="room" && $_GET["type"]!="invites" && $_GET["type"]!="change") {fail("Tipo incorretto di chiamata API. Contatta un tecnico");}

$dati = array();
if($_GET["type"]!="change") {
    $mondayStamp = getMondayStamp($_GET["today"]);
    $weekdays = getWeekdays();

    $bookings = get_bookings($_GET["primkey"], $mondayStamp, $_GET["type"]);


    if ($_GET["type"]!="invites") {
        $week = array();
        
        for ($g = 0; $g < count($weekdays); $g++) {
            
            $week[$g] = array($weekdays[$g], date("j".($_GET["type"]=="invites"?"/m":""), strtotime("+".$g." days", $mondayStamp)));
            
        }
        $dati["week"] = $week;
        $dati["weekstart"] = date("d/m/Y", $mondayStamp);
    }

    $dati["bookings"] = $bookings;
} else {
    //fail($_GET);
    $idp = $_GET["primkey"];
    $action = esiste("action", $_GET);
    $just = esiste("just", $_GET);
    
    if ($action=="" || $idp=="") {
        fail('Non ci sono abbastanza dati per la chiamata API (change). Contatta un tecnico');
    }
    if($action<0 || $action>1) {
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
                    FROM prenotazione
                    WHERE IDPrenotazione = ?";

        $sqlCheck = "SELECT COUNT(*) AS conflitto
                    FROM prenotazione INNER JOIN invito
                    ON (prenotazione.IDPrenotazione = invito.IDPrenotazione)
                    WHERE invito.IscrittoEmail = ?
                    AND invito.Accettazione = 1
                    AND DataPren = (SELECT DataPren
                                    FROM prenotazione 
                                    WHERE IDPrenotazione = ?)
                    AND NOT (OraFine <= ? OR OraInizio >= ?);";

        try {
            $stmtIF = $cid->prepare($sqlIF);
            $stmtIF->bind_param("i", $idp);
            $stmtIF->execute();
            $resIF = $stmtIF->get_result()->fetch_assoc();

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
    $sqlChange = "UPDATE invito
                    SET Accettazione = ?, Motivazione = ?
                    WHERE IDPrenotazione = ?
                    AND IscrittoEmail = ?";

    try {
        $stmtChange = $cid->prepare($sqlChange);

        $stmtChange->bind_param("isis", $action, $just, $idp, $user);
        $stmtChange->execute();
    } catch (exception $e) {
        $errorMessage = $e->getMessage();
        fail("Contatta un tecnico: ".$errorMessage);
    }

    success("Invito ".($action==1?"accettato":"rifiutato")." con successo");

}

//$dati["query"] = $qry;
/*$sched = get_room_schedule($inviti);
$dati["sched"] = $sched;*/

echo json_encode(["success" => true, "dati" => $dati]);

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
    $query = "SELECT invito.IDPrenotazione, Attivita, DataPren, OraInizio, OraFine, Accettazione
            FROM prenotazione INNER JOIN invito
            ON (prenotazione.IDPrenotazione = invito.IDPrenotazione";
    $query .= " AND \"".$lunedi."\" <= prenotazione.DataPren";
    if ($data2 != -1) {
        $query .= " AND prenotazione.DataPren <= \"".$domenica."\"";
    }
    $query .= " AND invito.IscrittoEmail=\"".$email."\"";
    $query .= " AND (invito.Accettazione ".($data2==-1?"IS NULL OR invito.Accettazione=0":"= 1").")";
    $query .= ")";
    $query .= " ORDER BY DataPren";

    return $query;
    
}

function room_bookings_query(string $room, int $data) {

    $lunedi = date("Y-m-d", $data);
    $domenica = date("Y-m-d", strtotime("+6 days", $data));
    $query = "SELECT Attivita, DataPren, OraInizio, OraFine
            FROM prenotazione";
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
                $bookings[$dataPrenStamp]["wkday"] = $weekdays[date("w",$dataPrenStamp)]." ".date("d/m",$dataPrenStamp);
                $bookings[$dataPrenStamp]["attivita"][] = creaAtt($row["Attivita"]." (".$orari.")", $dataPrenStamp, $status, $init, $end, $row["IDPrenotazione"]);
            } else {

                if ($action=="week") {$status = "attAccettata";}
                $bookings[$dayIdx][] = creaAtt($row["Attivita"], $dataPrenStamp, $status, $init, $end, $row["IDPrenotazione"]);
        }    
            }
            
            //echo "<p>".$init."<br></p>";
            
            
    }

    return $bookings;

}

?>