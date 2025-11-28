<?php
// Includiamo l'header comune (Navbar e apertura HTML)
include 'common/navbar.php';
?>

<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/style.css">

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlayRoomPlanner - Home</title>
</head>

<body>

    <div class="container mt-5 text-center">
        <h1 class="display-4">Benvenuto in PlayRoomPlanner</h1>

        <p class="lead">Mettiamo a disposizione 3 settori adatti ad ogni esigenza:</p>

        <hr class="my-4">

        <div class="row">
            <!-- box danza -->
            <div class="col-md-4">
                <div class="card text-white bg-dark mb-3"
                    style="background-image: url('immagini/danza.jpg'); background-size: cover; height: 200px;">
                    <div class="card-body d-flex align-items-center justify-content-center"
                        style="background-color: rgba(49, 46, 46, 0.5);">
                        <h3 class="card-title">Danza</h3>
                    </div>
                </div>
            </div>

            <!-- box musica -->
            <div class="col-md-4">
                <div class="card text-white bg-dark mb-3"
                    style="background-image: url('immagini/musica.jpg'); background-size: cover; height: 200px;">
                    <div class="card-body d-flex align-items-center justify-content-center"
                        style="background-color: rgba(114, 104, 104, 0.5);">
                        <h3 class="card-title">Musica</h3>
                    </div>
                </div>
            </div>

            <!-- box teatro -->
            <div class="col-md-4">
                <div class="card text-white bg-dark mb-3"
                    style="background-image: url('immagini/teatro.jpg'); background-size: cover; height: 200px;">
                    <div class="card-body d-flex align-items-center justify-content-center"
                        style="background-color: rgba(153, 142, 142, 0.5);">
                        <h3 class="card-title">Teatro</h3>
                    </div>
                </div>
            </div>
        </div>

    </div>

</body>

</html>



<?php
include 'common/footer.php';
?>