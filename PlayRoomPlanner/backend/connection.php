<?php
function connessione()
{

  $hostname = "localhost";
  $username = "root";
  $password = "";
  $dbname   = "test";
  
  try {
    $cid = new mysqli($hostname, $username, $password, $dbname);
    if ($cid->connect_error) {
      echo ("Errore di connessione al db $dbname: " . $cid->connect_error);
      $cid = null;
    }
  } catch (Exception $e) {
    $cid = null;
  }
  return $cid;
}

?>