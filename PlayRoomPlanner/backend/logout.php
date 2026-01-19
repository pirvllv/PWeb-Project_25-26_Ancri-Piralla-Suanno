<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION = array();

// distrugge variabili di sessione
if (ini_get("session.use_cookies")) {
  $params = session_get_cookie_params();
  setcookie(
    session_name(),
    '',
    time() - 42000,
    $params["path"],
    $params["domain"],
    $params["secure"],
    $params["httponly"]
  );
}

// distrugge la sessione.
session_destroy();

header("Location: /PlayRoomPlanner/index.php");
exit;
?>