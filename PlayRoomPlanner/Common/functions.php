<?php
function creaAtt(string $att, string $stato, int $orainizio, int $orafine) {

    return array("attivita"=>$att, "stato"=>$stato, "orainizio"=>$orainizio, "orafine"=>$orafine);

}

function get_user_schedule(string $email, string $data) {

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

    return $schedule;

}

function table_from_schedule($sched, $hmin, $hmax) {

    $table = "";
    for ($g = 0; $g < count($sched); $g++) {

        foreach($sched[$g] as $att) {

            if ($att["orafine"]<=$att["orainizio"]) {continue;}

            $column = $g+2;
            $row = 1+$att["orainizio"]-$hmin+1;
            $span = $att["orafine"]-$att["orainizio"]+$row;
            //$table = $table."\n<div class=\"act ".$att["stato"]."\" style=\"grid-column:".$column."/ span 1; grid-row:".$row."/span ".$span."\">".$att["attivita"]."</div>";
            $table = $table."\n<div class=\"cell ".$att["stato"]."\" style=\"grid-area: ".$row."/".$column."/".$span."/".$column.";\">".$att["attivita"]."</div>";

        }

    }

    return $table;

}
?>