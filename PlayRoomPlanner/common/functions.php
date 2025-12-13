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
    //print($var);
    if (isset($arr[$var])) {return $arr[$var];}
    return "";
}

function getWeekdays() {

    return array("Lun", "Mar", "Mer", "Gio", "Ven", "Sab", "Dom");
}

function getIndexes() {

    $weekdays = getWeekdays();
    for ($g = 0; $g < count($weekdays); $g++) {

        echo "<div class=\"".$weekdays[$g]." cell index\"; style=\"grid-area: 1 / ".($g+2)."/ 3 / span 1;\"></div>";

    }

    for ($h = 0; $h < 11; $h++) {

        echo "<div class=\"cell index\"; style=\"grid-area: ".(2*($h+1)+1)."/"."1/ span 2 /1;\">".($h+8).":00</div>";

    }
    
}

?>