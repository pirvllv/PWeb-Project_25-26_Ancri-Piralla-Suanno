<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    

        <!-- Bootstrap core CSS -->
        <link href="../css/bootstrap.min.css" rel="stylesheet">

        <!-- Additional CSS Files -->
        <link rel="stylesheet" href="../css/fontawesome.css">
        <link rel="stylesheet" href="../css/templatemo-574-mexant.css">
        <link rel="stylesheet" href="../css/owl.css">
        <link rel="stylesheet" href="../css/animate.css">
        <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css">

        <title>Gestione prenotazioni</title>
    </head>

    <body>
        <div class="container-calendario">
            <div class="testo-calendario">
                <h1>Gestore prenotazioni</h1>
                <p>Scegli la settimana da gestire</p>
            </div>

            <div class="boxSettimana">
                <div class="nav-settimana">
                    <button id="settPrec" class="orange-button">Prec</button>
                    <span id="labelSett"></span>
                    <button id="settSucc" class="orange-button">Succ</button>
                </div>

                <ul class="settimana grid-settimana" id="settimana-nome>
                    <li>Lun</li><li>Mar</li><li>Mer</li><li>Gio</li><li>Ven</li><li>Sab</li><li>Dom</li>
                </ul>

                <ul class="settimana grid-settimana" id="settimana-value"></ul>
            </div>
        </div>
        <script src="../js/calendario.js"></script>
        
        <div class="select-sala">
            <p>Scegli la sala prove</p>
            <select name="salaProve">
                <option>Musica d'insieme - 101</option>
                <option>Teatro lirico - 102</option>
                <option>Danza classica - 103</option>
            </select>
        </div>

    </body>

</html>