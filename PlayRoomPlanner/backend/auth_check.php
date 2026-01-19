<?php
// codice da includere in ogni pagina alla quale vogliamo che accedano solo utenti loggati


if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// se l'utente non è loggato (quindi la variabil e logged_in non è settata o è false), lo reindirizza al login
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
  // salva la pagina richiesta per il redirect dopo il login
  $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI'];
  header("Location: ../frontend/login.php");
  exit;
}
?>