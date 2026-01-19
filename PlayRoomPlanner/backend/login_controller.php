<?php
require_once '../backend/connection.php';

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $user = $_POST['email'];
  $password = $_POST['password'];

  $cid = connessione($hostname, $username, $password_db, $dbname);

  if ($cid) {
    $stmt = $cid->prepare("SELECT Email, Nome, Cognome, Ruolo, Password FROM Iscritto WHERE Email = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      $row = $result->fetch_assoc();

      if (password_verify($password, $row['Password'])) {
        $_SESSION['logged_in'] = true;
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


        if (isset($_SESSION['redirect_to'])) {
          $redirect_url = $_SESSION['redirect_to'];
          unset($_SESSION['redirect_to']);
          header("Location: " . $redirect_url);
        } else {
          header("Location: ../frontend/area_personale.php");
        }
        exit;
      } else {
        $error = "Password non corretta.";
      }
    } else {
      $error = "Email non trovata.";
    }
    $cid->close();
  } else {
    $error = "Errore di connessione al database.";
  }

  $_SESSION['login_error'] = $error;
  header("Location: ../frontend/login.php");
  exit;
}
?>