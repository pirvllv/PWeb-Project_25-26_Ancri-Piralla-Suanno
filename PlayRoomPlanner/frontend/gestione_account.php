<!DOCTYPE html>
<?php
require_once("../backend/auth_check.php");
require_once("../common/functions.php");
require_once("../backend/connection.php");

session_start();
// $cid = connessione();
?>
<html>
    <head>
        <title>Gestione account</title>
        <link href="../css/custom_style.css" rel="stylesheet">
        <link rel="stylesheet" href="..\css\fontawesome.css">
        <link rel="stylesheet" href="..\css\templatemo-574-mexant.css">
        <link rel="stylesheet" href="..\css\owl.css">
        <link rel="stylesheet" href="..\css\animate.css">
        
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    </head>
    <body id="gestione-account">
        <?php
        include '../common/navbar.php'
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
                        <h6>Ciao <?php echo $_SESSION['user']?></h6>
                        <h4>I tuoi dati</h4>
                      </div>
                    </div>
                    <div class="col-lg-10 offset-lg-1">
                      <form id="contact" action="../backend/user_data_API.php" method="post">
                        <div class="row">
                          <div class="col-lg-6">
                            <fieldset>
                              <input type="text" name="name" id="name" placeholder="Nome..." autocomplete="on" required>
                            </fieldset>
                          </div>
                          <div class="col-lg-6">
                            <fieldset>
                              <input type="text" name="surname" id="surname" placeholder="Cognome..." autocomplete="on" required>
                            </fieldset>
                          </div>
                          <div class="col-lg-6">
                            <fieldset>
                              <input type="email" name="email" id="email" pattern="[^ @]*@[^ @]*" placeholder="E-mail..." required="">
                            </fieldset>
                          </div>
                          <div class="col-lg-6">
                            <fieldset>
                              <input type="password" name="pwd" id="pwd" placeholder="Password..." required="">
                            </fieldset>
                          </div>
                          <div class="col-lg-6">
                            <fieldset>
                              <label for="DOB">Data di nascita</label><br>
                              <input type="date" name="DOB" id="DOB" placeholder="Data di nascita..." autocomplete="on" >
                            </fieldset>
                          </div>
                          <div class="col-lg-6">
                            <fieldset>
                              <input type="text" name="photo" id="photo" placeholder="Foto (link)..." autocomplete="on" >
                            </fieldset>
                          </div>
                          <div class="col-lg-6">
                            <fieldset style="display: flex;">
                              <input type="radio" id="studente" name="role" value="studente">
                              <label for="studente">Studente</label>
                              <input type="radio" id="tecnico" name="role" value="tecnico">
                              <label for="tecnico">Tecnico</label>
                              <input type="radio" id="docente" name="role" value="docente">
                              <label for="docente">Docente</label>
                              <input type="radio" id="responsabile" name="role" value="responsabile">
                              <label for="responsabile">Responsabile</label>
                            </fieldset>
                          </div>
                          <div class="col-lg-6">
                            <fieldset style="display: flex;">
                              <input type="radio" id="modifica" name="action" value="modifica">
                              <label for="modifica">Modifica</label>
                              <input type="radio" id="elimina" name="action" value="elimina">
                              <label for="elimina">Elimina</label>
                              <input type="radio" id="inserisci" name="action" value="inserisci">
                              <label for="inserisci">Inserisci</label>
                            </fieldset>
                          </div>
                          <div class="col-lg-12">
                            <fieldset>
                                <button type="submit" id="account-data-submit" class="orange-button">Modifica dati</button>
                                <button type="reset" id="account-data-cancel" class="orange-button">Annulla</button>
                                <!--button type="button" id="account-data-erase" class="orange-button">Elimina i tuoi dati</button-->
                            </fieldset>
                          </div>
                        </div>
                      </form>
                    </div>
                </div>
            </div>
        </section>
        <?php
        include '../common/footer.php';
        ?>
    </body>
</html>