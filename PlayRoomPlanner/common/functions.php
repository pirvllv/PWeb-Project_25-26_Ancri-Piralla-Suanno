<?php
include "../config.php";
require_once $root."/backend/connection.php";

function getCss() {

	echo '<link rel="stylesheet" href="/PlayRoomPlanner/css/bootstrap.min.css">
        <link rel="stylesheet" href="/PlayRoomPlanner/css/fontawesome.css">
        <link rel="stylesheet" href="/PlayRoomPlanner/css/templatemo-574-mexant.css">
        <link rel="stylesheet" href="/PlayRoomPlanner/css/owl.css">
        <link rel="stylesheet" href="/PlayRoomPlanner/css/animate.css">
		<link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css">
        
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">';
	
}

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


// funzione utilizzata in sale_prova.php che serve per mostrare le sale in base al settore, già formattate in HTML
function mostraSale($cid, $tipologia)
{
  $sql = "SELECT SP.NumAula, SP.Capienza, SP.SettoreNome 
            FROM SalaProve SP 
            JOIN Settore S ON SP.SettoreNome = S.Nome 
            WHERE S.Tipologia = '$tipologia'
            ORDER BY SP.NumAula ASC";

  $result = $cid->query($sql);

  if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $numAula = htmlspecialchars($row["NumAula"]);
      $capienza = htmlspecialchars($row["Capienza"]);
      $nomeSettore = htmlspecialchars($row["SettoreNome"]);

      echo <<<HTML
            <div class="col-lg-12">
						  <div class="service-item">
							  <div class="row">
								  <div class="col-lg-4">
									  <div class="icon">
										  <img src="../immagini/{$tipologia}_{$numAula}.jpg" alt="Sala Prova {$numAula}">
					  				</div>
					  			</div>
						  		<div class="col-lg-8">
							  		<div class="right-content">
								  		<h4>Sala Prova {$numAula}</h4>
                      <p><strong>Tipologia:</strong> {$tipologia}</p>
                      <p><strong>Capienza:</strong> {$capienza} persone</p>
                      <p><strong>Settore:</strong> {$nomeSettore}</p>
									  </div>
								  </div>
							  </div>
						  </div>
				    </div>
HTML;
    }
  } else {
    echo '<div class="row"><div class="col-12"><p class="text-center text-muted">Nessuna sala disponibile per questa categoria al momento.</p></div></div>';
  }
}

?>