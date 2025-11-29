<!DOCTYPE html>

<html>
    <head>
        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <link rel="stylesheet" href="../css/style.css">
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
                    <button id="settPrec">Prec</button>
                    <span id="labelSett"></span>
                    <button id="settSucc">Succ</button>
                </div>

                <ul class="giorni">
                    <li>Lun</li><li>Mar</li><li>Mer</li><li>Gio</li><li>Ven</li><li>Sab</li><li>Dom</li>
                </ul>

                <ul class="settimana" id="settimana"></ul>
            </div>
        </div>
        <script src="calendario.js"></script>
        
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