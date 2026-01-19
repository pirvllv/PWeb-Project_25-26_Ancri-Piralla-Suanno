<?php
require_once "../backend/connection.php";

function getCss() {

	echo '<link rel="stylesheet" href="../css/bootstrap.min.css">
        <link rel="stylesheet" href="../css/fontawesome.css">
        <link rel="stylesheet" href="../css/templatemo-574-mexant.css">
        <link rel="stylesheet" href="../css/owl.css">
        <link rel="stylesheet" href="../css/animate.css">
		<link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css">
        
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">';
	
}


function fail($message) {

    echo json_encode(['success' => false, 'message' => $message]);
    exit();
}

function success($message) {

    echo json_encode(['success' => true, 'message' => $message]);
    exit();
}

function getUserDataForm($type) {

    if ($type !== "register" && $type!=="account") {
        echo "Errore nella creazione del form.";
        return;
    }

    $readOnly = ($type=="account")?" readOnly ":"";
    $readOnlyClass = ($type=="account")?" read-only ":"";
    $display = ($type=="account")?' style="display: none; "':"";
    $disabled = ($type=="account")?" disabled ":"";

    echo '<form id="contact">
            <div class="row">
                <div class="col-lg-6">
                <fieldset>
                    <label for="name">Nome</label><br>
                    <input class="user-data-field '.$readOnlyClass.'" type="text" id="name" autocomplete="on" '.$readOnly.'></input>
                </fieldset>
                </div>
                <div class="col-lg-6">
                <fieldset>
                    <label for="surname">Cognome</label><br>
                    <input class="user-data-field '.$readOnlyClass.'" type="text" id="surname" autocomplete="on" '.$readOnly.'></input>
                </fieldset>
                </div>
                <div class="col-lg-6">
                <fieldset>
                    <label for="email">Email</label><br>
                    <input class="user-data-field '.$readOnlyClass.'" type="email" id="email" autocomplete="on" '.$readOnly.'></input>
                </fieldset>
                </div>
                <div class="col-lg-6">
                <fieldset>
                    <input class="user-data-password" id="password" type="password" placeholder="'.($type==="account"?"Nuova password...":"Password...").'" '.$readOnly.$display.'></input>
                </fieldset>
                </div>
                <div class="col-lg-6">
                <fieldset>
                    <input class="user-data-password" id="password-conf" type="password" placeholder="Conferma password..." '.$readOnly.$display.'></input>
                </fieldset>
                </div>
                <div class="col-lg-6">
                <fieldset>
                    <label for="DOB">Data di nascita</label><br>
                    <input class="user-data-field '.$readOnlyClass.'" type="date" id="DOB" autocomplete="off" '.$readOnly.'></input>
                </fieldset>
                </div>
                <div class="col-lg-6">
                <fieldset>
                    <label for="photo">Foto (link)</label><br>
                    <input class="user-data-field '.$readOnlyClass.'" type="text" id="photo" autocomplete="on" '.$readOnly.'></input>
                </fieldset>
                </div>
                <div class="col-lg-6">
                <label for="ruolo-fieldset">Ruolo</label><br>
                <fieldset id="ruolo-fieldset" style="display: flex;">
                    <input class="user-data-role" type="radio" id="studente" name="role" value="studente" '.$disabled.'>
                    <label for="studente">Studente</label>
                    <input class="user-data-role" type="radio" id="tecnico" name="role" value="tecnico" '.$disabled.'>
                    <label for="tecnico">Tecnico</label>
                    <input class="user-data-role" type="radio" id="docente" name="role" value="docente" '.$disabled.'>
                    <label for="docente">Docente</label>
                </fieldset>
                </div>
                <div class="col-lg-12">
                <fieldset>';
                if ($type==="account") {
                    echo '<button type="button" id="account-data-enable" class="orange-button" onclick="abilita_modifica()">
                        Modifica dati</button>
                    <button type="button" id="account-data-submit" class="orange-button" onclick="conferma_modifica()" style="display: none;">
                        Conferma modifiche</button>
                    <button type="button" id="account-data-cancel" class="orange-button" onclick="annulla_modifica()" style="display: none;">
                        Annulla</button>
                    <button type="button" id="account-data-erase" class="orange-button" onclick="elimina_account()" style="display: none;">
                        Elimina il tuo account</button>';
                } else {
                    echo '<button type="button" id="account-data-create" class="orange-button" onclick="crea_account()">
                        Crea nuovo account</button>';
                }
    echo        '</fieldset>
                </div>
            </div>
        </form>';

}

function update_session() {

    $hostname = 'localhost';
    $username = 'root';
    $password_db = '';
    $dbname = 'playroomplanner';
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }   

    $cid = connessione($hostname, $username, $password_db, $dbname);
    $error = "";
    if ($cid) {
        $stmt = $cid->prepare("SELECT Email, Nome, Cognome, Ruolo FROM Iscritto WHERE Email = ?");
        $user = $_SESSION["user"];
        $stmt->bind_param("s", $user);
        try { 
            $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            return "Errore nella query di aggiornamento sessione";
        }
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();

            $_SESSION['user'] = $row['Email'];
            $_SESSION['nome'] = $row['Nome'];
            $_SESSION['cognome'] = $row['Cognome'];
            $_SESSION['ruolo'] = $row['Ruolo'];

            $sql = "SELECT COUNT(*) as isResp
                FROM Settore
                WHERE ResponsabileEmail = ?";

            $stmt = $cid->prepare($sql);
            $stmt->bind_param("s", $_SESSION['user']);
            $stmt->execute();
            $respCheck = $stmt->get_result()->fetch_assoc();

            $_SESSION['responsabile'] = ($respCheck['isResp'] > 0);
            
        } else {
            $error = "Email non trovata.";
        }
        $cid->close();
    } else {
        $error = "Errore di connessione al database.";
    }

    return $error;
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

        echo "<div class='".$weekdays[$g]." cell index'; style='grid-area: 1 / ".($g+2)."/ 3 / span 1;'></div>";

    }

    for ($h = 9; $h < 23; $h++) {

        echo "<div class='cell index ".($h<=18?"":"hsera")."'; style='grid-area: ".(2*($h-9+1)+1)."/"."1/ span 2 /1;'>".($h).":00</div>";

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
										  <img src="/PlayRoomPlanner/immagini/{$tipologia}_{$numAula}.jpg" alt="Sala Prova {$numAula}">
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