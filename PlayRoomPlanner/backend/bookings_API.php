<?php

require_once("../common/functions.php");
require_once("../backend/connection.php");

// $cid = connessione();
$qry = "";

if (!$cid) { fail("Connessione al database non riuscita"); }
//print_r($_GET);
if (esiste("today", $_GET)=="" || esiste("primkey", $_GET)=="" || esiste("type", $_GET)=="") {
    fail('Non ci sono abbastanza dati per la chiamata API');
}
if($_GET["type"]!="week" && $_GET["type"]!="room" && $_GET["type"]!="invites") {fail("Tipo incorretto di chiamata API");}

$mondayStamp = getMondayStamp($_GET["today"]);
$week = array();
$weekdays = getWeekdays();
for ($g = 0; $g < count($weekdays); $g++) {
    
    $week[$weekdays[$g]] = date("d", strtotime("+".$g." days", $mondayStamp));
    
}

$dati = array("week" => $week);
$dati["weekstart"] = date("d/m/Y", $mondayStamp);

$bookings = get_bookings($_GET["primkey"], $mondayStamp, $_GET["type"]);

$dati["bookings"] = $bookings;
$dati["query"] = $qry;
/*$sched = get_room_schedule($inviti);
$dati["sched"] = $sched;*/

echo json_encode(["success" => true, "dati" => $dati]);

function getMondayStamp($stamp) {

    return strtotime("+1 Day", strtotime("Last Sunday", $stamp));
    
}

function creaAtt(string $att, $data, string $stato, int $secinizio, float $secfine) {

    //secinizio e secfine sono timestamp
    return array("attivita"=>$att, "data" => $data, "stato"=>$stato, "orainizio"=>$secinizio, "orafine"=>$secfine);

}

function displayAtt($att) {

    $html = "";
    try {

        $html .= "<div class=\"cell ".$att["stato"]."\">";
        $html .= date("H:i",$att["orainizio"]+$att["data"])." - ".$att["attivita"]."</div>";
        
    } catch (exception $e) {

        return "<div class=\"cell\">Attività errata</div>";
        
    }

    return $html;
    
}

function get_user_schedule(&$invites) {

    $schedule = array();
    foreach ($invites as $i => $day) {

        foreach ($day as $act) {
        //echo "<p>".print_r($act)."<br></p>";
            if ($act["stato"] == "attAccettata") { $schedule[$i][] = $act;}
            
        }
        
    }

    return $schedule;

}

function get_room_schedule(&$invites) {

    $schedule = array();
    foreach ($invites as $i => $day) {

        foreach ($day as $act) {
        //echo "<p>".print_r($act)."<br></p>";
            if ($act["stato"] == "attNeutra") { $schedule[$i][] = $act;}
            
        }
        
    }

    return $schedule;

}

function user_invites_query(string $email, int $data1, int $data2) {

    $lunedi = date("Y-m-d", $data1);
    $domenica = date("Y-m-d", $data2);
    $query = "SELECT Attivita, DataPren, OraInizio, OraFine, Accettazione
            FROM prenotazione INNER JOIN invito
            ON (prenotazione.IDPrenotazione = invito.IDPrenotazione";
    $query .= " AND \"".$lunedi."\" <= prenotazione.DataPren";
    if ($data2 != -1) {
        $query .= " AND prenotazione.DataPren <= \"".$domenica."\"";
    }
    $query .= " AND invito.IscrittoEmail=\"".$email."\"";
    $query .= " AND invito.Accettazione ".($data2==-1?"!":"")."= 1";
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

    $cid = connessione();
    if(!$cid) {die("Errore di connessione al database");}

    $todayStamp = strtotime("10-12-2025");
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
        fail($errorMessage);
    }
    $cid->close();

    $bookings = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            //echo "<p>".print_r($row)."<br></p>";
            //echo "\n\n";
            $dataPrenStamp = strtotime($row["DataPren"]);
            $dayIdx = intval(date("N", $dataPrenStamp))-1;
            $status = "";
            if ($action=="invites") {
                switch ($row["Accettazione"]) {
                    case "": $status="attInSospeso"; break;
                    case "0": $status="attRifiutata"; break;
                    //case "1": $status="attAccettata"; break;
                }
            } else if ($action=="week") {$status = "attAccettata";}
            else { $status = "attNeutra";}         
            
            $initAr = explode(":", $row["OraInizio"]);
            $init = $initAr[0]*3600+1800*(int)($initAr[1]/30);

            $endAr = explode(":", $row["OraFine"]);
            $end = $endAr[0]*3600+1800*(int)($endAr[1]/30);
            //echo "<p>".$init."<br></p>";
            
            $bookings[$dayIdx][] = creaAtt($row["Attivita"], $dataPrenStamp, $status, $init, $end);
        }
    }

    return $bookings;

}

function table_from_schedule($sched, $hmin, $hmax) {

    $table = "";
    foreach ($sched as $g => $day) {

        foreach($day as $att) {

            if ($att["orafine"]<=$att["orainizio"]) {continue;}

            $column = $g+2;
            $row = 2*($att["orainizio"]/3600-$hmin+1)+1;
            $span = ($att["orafine"]-$att["orainizio"])/1800;
            
            $table = $table."\n<div class=\"cell ".$att["stato"]."\" style=\"grid-area: ".$row."/".$column."/ span ".$span."/".$column.";\">".$att["attivita"]."</div>";

        }

    }

    return $table;

}

function fail($message) {

    echo json_encode(['success' => false, 'message' => $message]);
    exit();
}

?>