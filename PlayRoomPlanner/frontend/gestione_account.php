<!DOCTYPE html>
<?php
require_once "../backend/auth_check.php";
require_once "../common/functions.php";
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

?>
<html>
    <head>
        <title>Gestione account</title>
        <link href="../css/custom_style.css" rel="stylesheet">
        <?php getCss(); ?>
        <script src="../js/userData.js"></script>
        <script src="../common/functions.js"></script>
        <script>
          window.sessionData = {
            username: "<?php echo $_SESSION['user']; ?>"
          };
        </script>
    </head>
    <body id="gestione-account">
        <?php
        include "../common/navbar.php";
        ?>
        <div class="page-heading">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="header-text">
                            <h2>Gestione account</h2>
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
                        <h6>Ciao <?php echo $_SESSION['nome']?></h6>
                        <h4>I tuoi dati</h4>
                      </div>
                    </div>
                    <div class="col-lg-10 offset-lg-1">
                      <form id="contact">
                        <div class="row">
                          <div class="col-lg-6">
                            <fieldset>
                              <label for="name">Nome</label><br>
                              <input class="user-data-field read-only" type="text" id="name" autocomplete="on" readOnly></input>
                            </fieldset>
                          </div>
                          <div class="col-lg-6">
                            <fieldset>
                              <label for="surname">Cognome</label><br>
                              <input class="user-data-field read-only" type="text" id="surname" autocomplete="on" readOnly></input>
                            </fieldset>
                          </div>
                          <div class="col-lg-6">
                            <fieldset>
                              <label for="email">Email</label><br>
                              <input class="user-data-field read-only" type="email" id="email" pattern="[^ @]*@[^ @]*" readOnly></input>
                            </fieldset>
                          </div>
                          <div class="col-lg-6">
                            <fieldset>
                              <input class="user-data-password" id="password" type="password" placeholder="Nuova password..." readOnly style="display: none;"></input>
                            </fieldset>
                          </div>
                          <div class="col-lg-6">
                            <fieldset>
                              <input class="user-data-password" id="password-conf" type="password" placeholder="Conferma password..." readOnly style="display: none;"></input>
                            </fieldset>
                          </div>
                          <div class="col-lg-6">
                            <fieldset>
                              <label for="DOB">Data di nascita</label><br>
                              <input class="user-data-field read-only" type="date" id="DOB" autocomplete="off" readOnly></input>
                            </fieldset>
                          </div>
                          <div class="col-lg-6">
                            <fieldset>
                              <label for="photo">Foto (link)</label><br>
                              <input class="user-data-field read-only" type="text" id="photo" autocomplete="on" readOnly></input>
                            </fieldset>
                          </div>
                          <div class="col-lg-6">
                            <label for="ruolo-fieldset">Ruolo</label><br>
                            <fieldset id="ruolo-fieldset" style="display: flex;">
                              <input class="user-data-role" type="radio" id="studente" name="role" value="studente" disabled>
                              <label for="studente">Studente</label>
                              <input class="user-data-role" type="radio" id="tecnico" name="role" value="tecnico" disabled>
                              <label for="tecnico">Tecnico</label>
                              <input class="user-data-role" type="radio" id="docente" name="role" value="docente" disabled>
                              <label for="docente">Docente</label>
                              <input class="user-data-role" type="radio" id="responsabile" name="role" value="responsabile" disabled>
                              <label for="responsabile">Responsabile</label>
                            </fieldset>
                          </div>
                          <div class="col-lg-12">
                            <fieldset>
                                <button type="button" id="account-data-enable" class="orange-button" onclick="abilita_modifica()">
                                  Modifica dati</button>
                                <button type="button" id="account-data-submit" class="orange-button" onclick="conferma_modifica()" style="display: none;">
                                  Conferma modifiche</button>
                                <button type="button" id="account-data-cancel" class="orange-button" onclick="annulla_modifica()" style="display: none;">
                                  Annulla</button>
                                <button type="button" id="account-data-erase" class="orange-button" onclick="elimina_account()" style="display: none;">
                                  Elimina il tuo account</button>
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