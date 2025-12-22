<!DOCTYPE html>
<?php
require_once ("../common/functions.php");

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (isset($_SESSION['logged_in'])) {
  if ($_SESSION['logged_in'] === true) {
    header("Location: ../index.php");
    exit;
  }
}

?>
<html>
    <head>
        <title>Nuovo account</title>
        <link href="../css/custom_style.css" rel="stylesheet">
        <?php getCss(); ?>
        <script src="../js/userData.js"></script>
    </head>
    <body id="nuovo-account">
        <?php
        include "../common/navbar.php";
        ?>
        <div class="page-heading">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="header-text">
                            <h2>Nuovo account</h2>
                            <div class="div-dec"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <section class="services">
            <div class="account-data-form service-item">
                <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                      <div class="section-heading">
                        <h4>Nuovo utente</h4>
                      </div>
                    </div>
                    <div class="col-lg-10 offset-lg-1">
                      <form id="contact">
                        <div class="row">
                          <div class="col-lg-6">
                            <fieldset>
                              <label for="name">Nome</label><br>
                              <input class="user-data-field" type="text" id="name" autocomplete="on" ></input>
                            </fieldset>
                          </div>
                          <div class="col-lg-6">
                            <fieldset>
                              <label for="surname">Cognome</label><br>
                              <input class="user-data-field" type="text" id="surname" autocomplete="on" ></input>
                            </fieldset>
                          </div>
                          <div class="col-lg-6">
                            <fieldset>
                              <label for="email">Email</label><br>
                              <input class="user-data-field" type="email" id="email" pattern="[^ @]*@[^ @]*" ></input>
                            </fieldset>
                          </div>
                          <div class="col-lg-6">
                            <fieldset>
                              <label for="password">Password</label><br>
                              <input class="user-data-password" id="password" type="password" ></input>
                            </fieldset>
                          </div>
                          <div class="col-lg-6">
                            <fieldset>
                              <label for="password-conf">Conferma password</label><br>
                              <input class="user-data-password" id="password-conf" type="password" ></input>
                            </fieldset>
                          </div>
                          <div class="col-lg-6">
                            <fieldset>
                              <label for="DOB">Data di nascita</label><br>
                              <input class="user-data-field" type="date" id="DOB" autocomplete="off"></input>
                            </fieldset>
                          </div>
                          <div class="col-lg-6">
                            <fieldset>
                              <label for="photo">Foto (link)</label><br>
                              <input class="user-data-field" type="text" id="photo" autocomplete="on"></input>
                            </fieldset>
                          </div>
                          <div class="col-lg-6">
                            <label for="ruolo-fieldset">Ruolo</label><br>
                            <fieldset id="ruolo-fieldset" style="display: flex;">
                              <input class="user-data-role" type="radio" id="studente" name="role" value="studente">
                              <label for="studente">Studente</label>
                              <input class="user-data-role" type="radio" id="tecnico" name="role" value="tecnico">
                              <label for="tecnico">Tecnico</label>
                              <input class="user-data-role" type="radio" id="docente" name="role" value="docente">
                              <label for="docente">Docente</label>
                              <!--input class="user-data-role" type="radio" id="responsabile" name="role" value="responsabile">
                              <label for="responsabile">Responsabile</label-->
                            </fieldset>
                          </div>
                          <div class="col-lg-12">
                            <fieldset>
                                <button type="button" id="account-data-create" class="orange-button" onclick="crea_account()">
                                  Crea nuovo account</button>
                            </fieldset>
                          </div>
                        </div>
                      </form>
                    </div>
                </div>
            </div>
        </section>
        <?php
        include "../common/footer.php";
        ?>
    </body>
</html>