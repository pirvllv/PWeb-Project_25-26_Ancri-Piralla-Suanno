<?php

include_once("../backend/connection.php");

//Si aspetta un array associativo con coppie "nomeattributo" => "valoreattributo"
function insert_query($values, $table) {
    
    if (empty($values)) {return -1;}
    
    $keys = ""; $valori = "";
    foreach ($values as $key => $value) {
        $keys .= $key.",";
        $valori .= "\"".$value."\",";
    }
    $keys = rtrim($keys, ", ");
    $valori = rtrim($valori, ", ");
    
    $query = "INSERT INTO ".$table." (".$keys.") VALUES (".$valori.")";

    return $query;
}

//Si aspetta un array associativo con coppie "nomeattributo" => "valoreattributo"
//$entry_id e $entry_id_name sono rispettivamente il valore e il nome attributo della chiave primaria della tupla da aggiornare
function update_query($values, $table, $entry_id, $entry_id_name) {
    if (empty($values)) {return -1;}
    $query = "UPDATE ".$table." SET ";
    foreach ($values as $key => $value) {
        $pair = $key." = \"".$value."\"";
        $query = $query.$pair.", ";
    }
    $query = rtrim($query, ", ");
    $query = $query." WHERE ".$entry_id_name." = \"".$entry_id."\"";
    return $query;
}

//$entry_id e $entry_id_name sono rispettivamente il valore e il nome attributo della chiave primaria della tupla da eliminare
function delete_query($table, $entry_id, $entry_id_name) {
    $query = "DELETE FROM ".$table." WHERE ".$entry_id_name." = \"".$entry_id."\"";
    return $query;
}

function esiste($var, &$arr) {
    if (isset($arr["$var"])) {return $_POST["$var"];}
    return "";
}

//Funzioni dell'area personale
function creaAtt(string $att, string $stato, float $orainizio, float $orafine) {

    return array("attivita"=>$att, "stato"=>$stato, "orainizio"=>$orainizio, "orafine"=>$orafine);

}

function get_user_schedule(string $email, string $data) {

    $cid = connessione();
    $schedule = array();
    for ($i = 0; $i<7; $i++) {
        //Codice 0 = in sospeso
        //Codice 1 = accettato
        //Codice 2 = rifiutato
        $schedule[] = array();
        $schedule[$i][] = creaAtt("Attività 1", "attAccettata", 8.5, 11.5);
        $schedule[$i][] = creaAtt("Attività 2", "attAccettata", 12, 13);
        $schedule[$i][] = creaAtt("Attività 3", "attAccettata", 13, 14);

    }

    $cid->close();
    return $schedule;

    /*
    Esegue get_user_invites e poi prende solo quelle accettate
    */

}

function get_user_invites(string $email, string $data) {

    $cid = connessione();
    $schedule = array();
    for ($i = 0; $i<7; $i++) {
        //Codice 0 = in sospeso
        //Codice 1 = accettato
        //Codice 2 = rifiutato
        $schedule[] = array();
        $schedule[$i][] = creaAtt("Attività in sospeso", "attInSospeso", 8, 11);
        $schedule[$i][] = creaAtt("Attività accettata", "attAccettata", 12, 13);
        $schedule[$i][] = creaAtt("Attività rifiutata", "attRifiutata", 13, 14);

    }

    $cid->close();
    return $schedule;

    /*SELECT (Attivita, DataPren, OraInizio, OraFine)
    FROM Prenotazione
    WHERE ((Lunedi < DataPren < LunediDopo)
        AND IDPrenotazione IN (SELECT IDPrenotazione FROM invito WHERE (email=$email AND accettazione=true))
        )
    */

}

function table_from_schedule($sched, $hmin, $hmax) {

    $table = "";
    for ($g = 0; $g < count($sched); $g++) {

        foreach($sched[$g] as $att) {

            if ($att["orafine"]<=$att["orainizio"]) {continue;}

            $column = $g+2;
            $row = 2*($att["orainizio"]-$hmin+1)+1;
            $span = ($att["orafine"]-$att["orainizio"])*2;
            //$table = $table."\n<div class=\"act ".$att["stato"]."\" style=\"grid-column:".$column."/ span 1; grid-row:".$row."/span ".$span."\">".$att["attivita"]."</div>";
            $table = $table."\n<div class=\"cell ".$att["stato"]."\" style=\"grid-area: ".$row."/".$column."/ span ".$span."/".$column.";\">".$att["attivita"]."</div>";

        }

    }

    return $table;

}
?>