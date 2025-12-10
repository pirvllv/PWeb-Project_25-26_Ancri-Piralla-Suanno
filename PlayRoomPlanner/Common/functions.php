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

?>