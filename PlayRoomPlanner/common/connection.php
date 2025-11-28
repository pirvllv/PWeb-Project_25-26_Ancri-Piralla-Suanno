<?php

$hostname = 'localhost';
$username = 'root';
$password = '';
$dbname = 'playroomplanner';


function connessione($hostname, $username, $password, $dbname)
{
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

$cid = connessione($hostname, $username, $password, $dbname);


?>