document.addEventListener("DOMContentLoaded", function() {

    APIurl = "/PlayRoomPlanner/backend/user_data_API.php";
    roles = document.getElementsByName("role")
    fields = document.getElementsByClassName("user-data-field");
    passEl = document.getElementById("password");
    passConfEl = document.getElementById("password-conf");

    if (document.body.id=="gestione-account") {
        primkey = window.sessionData.username;
        datiCorrenti = [];
        caricaCampi();
        
    }
    
}); 

function logout() {

    fetch("/PlayRoomPlanner/backend/logout.php", {
        method: "POST",
        credential: "include"
    }).catch(error => console.error('Errore:' + error));
    window.location.href = "/PlayRoomPlanner/index.php";
    
}

function caricaCampi() {
    primkey = window.sessionData.username;
    console.log("email detected: "+ window.sessionData.username);
    console.log(APIurl);
        fetchBody = new FormData();
        fetchBody.append("primkey", primkey);
        fetchBody.append("action", "getData");
        
        fetch(APIurl, {
          method: "POST",
          body: fetchBody
        })
        .then(response => response.json())
        .then(data => {
            //console.log(data);
            //console.log("ciao");
            if (data.success) {

                datiCorrenti = data.dati;

                for (let i = 0; i < fields.length; i++) {
                    fields[i].value = datiCorrenti[fields[i].id];
                    //console.log(data.dati[fields[i].id]);
                }

                for (i = 0; i<roles.length; i++) {
                    //console.log(roles[i]);

                    if (roles[i].id == datiCorrenti["role"]) {
                        roles[i].checked = true;
                        roles[i].disabled = false;
                    }
                    
                }
                
                
                
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Errore:' + error));
}

function annulla_modifica() {

    caricaCampi();
    for (let i = 0; i < fields.length; i++) {
        fields[i].readOnly = true;
        fields[i].classList.add("read-only");
        //console.log(i);
    }
    
    for (let i = 0; i < roles.length; i++) {
        roles[i].disabled = true;
        //console.log(i);
    }

    document.getElementById("password").readOnly = true;
    document.getElementById("password").style.display = "none";
    document.getElementById("password-conf").readOnly = true;
    document.getElementById("password-conf").style.display = "none";
    document.getElementById("account-data-enable").style.display = "inline-block";
    document.getElementById("account-data-erase").style.display = "none";
    document.getElementById("account-data-cancel").style.display = "none";
    document.getElementById("account-data-submit").style.display = "none";
    
}

function elimina_account() {
    
    let confMess = "Sei sicuro di voler eliminare il tuo account? Questa azione è irreversibile";

    let fetchBody = new FormData();
    fetchBody.append("primkey", primkey);
    fetchBody.append("action", "elimina");

    if(!confirm(confMess)) {return;}
    
    fetch(APIurl, {
      method: "POST",
      body: fetchBody
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        //console.log("ciao");
        if (data.success) {
            alert(data.message);
            logout();
        } else {
            alert(data.message);
        }
    }).then({})
    .catch(error => console.error('Errore:' + error));
    
}

function crea_account() {
    
    let confMess = "Sei sicuro di voler creare un nuovo account?";

    let fetchBody = new FormData();
    fetchBody.append("action", "inserisci");

    let datiNuovi = [];
    for (let i = 0; i < fields.length; i++) {
        datiNuovi[fields[i].id] = fields[i].value;
        //console.log(datiNuovi[fields[i].id]);
    }

    if (passEl.value != passConfEl.value) {
        alert("Le password devono coincidere");
        return;
    } else if (passEl.value!="") {
        fetchBody.append("password", passEl.value);
    } else {
        alert("La password non può essere vuota");
        return;
    }

    for (let i = 0; i < fields.length; i++) {
        if (datiNuovi[fields[i].id] == "" && (fields[i].id!="photo")) {
            alert("Il campo "+fields[i].id+" non può essere vuoto.");
            return;
        }
        
        fetchBody.append(fields[i].id, datiNuovi[fields[i].id]);
    }
    
    let ROLE = "";
    for (i = 0; i<roles.length; i++) {

        if (roles[i].checked) {
            ROLE = roles[i].value;
        }
        
    }

    if (ROLE=="") {
        alert("Il campo ruolo non può essere vuoto.");
        return;
    }
    
    fetchBody.append("role", ROLE);

    if(!confirm(confMess)) {return;}
    
    fetch(APIurl, {
      method: "POST",
      body: fetchBody
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        //console.log("ciao");
        if (data.success) {
            alert(data.message);
            window.location.href = "/PlayRoomPlanner/frontend/login.php";
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Errore:' + error));
    
}

function conferma_modifica() {

    console.log("modifica dati");
    let logoutBool = false;
    
    let confMess = "Sei sicuro di voler modificare i dati?";

    let fetchBody = new FormData();
    fetchBody.append("primkey", primkey);
    fetchBody.append("action", "modifica");

    let datiNuovi = [];
    for (let i = 0; i < fields.length; i++) {
        datiNuovi[fields[i].id] = fields[i].value;
        //console.log(datiNuovi[fields[i].id]);
    }

    if (passEl.value != passConfEl.value) {
        alert("Le password devono coincidere");
        return;
    } else if (passEl.value!="") {
        fetchBody.append("password", passEl.value);
        logoutBool = true;
    }

    for (let i = 0; i < fields.length; i++) {
        if (datiNuovi[fields[i].id] == "" && (fields[i].id!="photo")) {
            alert("Il campo "+fields[i].id+" non può essere vuoto.");
            return;
        }
        
        if (datiNuovi[fields[i].id] != datiCorrenti[fields[i].id]) {
            fetchBody.append(fields[i].id, datiNuovi[fields[i].id]);
            if (fields[i].id=="email") {
                logoutBool = true;
            }
        }
    }

    if(logout) {
        confMess += " Stai modificando dati sensibili. Verrà effettuato il logout";
    }
    
    let ROLE = "";
    for (i = 0; i<roles.length; i++) {

        if (roles[i].checked) {
            ROLE = roles[i].value;
        }
        
    }

    if (ROLE=="") {
        alert("Il campo ruolo non può essere vuoto.");
        return;
    }
    
    if (ROLE != datiCorrenti["role"]) {
        fetchBody.append("role", ROLE);
    }

    if(!confirm(confMess)) {return;}
    
    fetch(APIurl, {
      method: "POST",
      body: fetchBody
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        //console.log("ciao");
        if (data.success) {
            alert(data.message);
            if(logoutBool) {
                logout();
            }
            annulla_modifica();
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Errore:' + error));
    
}

function abilita_modifica() {
    //console.log(datiCorrenti);

    for (let i = 0; i < fields.length; i++) {
        fields[i].readOnly = false;
        fields[i].classList.remove("read-only");
        //console.log(i);
    }

    let rols = document.getElementsByClassName("user-data-role");
    for (let i = 0; i < rols.length; i++) {
        rols[i].disabled = false;
        //console.log(i);
    }

    document.getElementById("password").readOnly = false;
    document.getElementById("password").style.display = "inline-block";
    document.getElementById("password-conf").readOnly = false;
    document.getElementById("password-conf").style.display = "inline-block";
    document.getElementById("account-data-enable").style.display = "none";
    document.getElementById("account-data-erase").style.display = "inline-block";
    document.getElementById("account-data-cancel").style.display = "inline-block";
    document.getElementById("account-data-submit").style.display = "inline-block";
    
}

