let imageFile;
let photoInput;
let imagePreview;

document.addEventListener("DOMContentLoaded", function() {

    APIurl = "../backend/user_data_API.php";
    roles = document.getElementsByName("role")
    fields = document.getElementsByClassName("user-data-field");
    passEl = document.getElementById("password");
    passConfEl = document.getElementById("password-conf");

    caricaFoto();

    if (document.body.id=="gestione-account" || document.body.id=="root-account") {
        primkey = window.sessionData.username;
        datiCorrenti = [];
        caricaCampi();
        
    }

    
    
});

function caricaFoto() {
    photoInput = document.getElementById("photo");
    imagePreview = document.getElementById('image-preview');

    photoInput.addEventListener("change", async () => {
       imageFile = photoInput.files[0];
       //console.log(imageFile);

        //CONTROLLARE LA DIMENSIONE DELL'IMMAGINE IN INGRESSO

        const reader = new FileReader();
        reader.onload = (e) => {
        imagePreview.src = e.target.result;
        };

        reader.onerror = (err) => {
            console.error("Error reading file:", err);
            alert("Errore nella lettura dell'immagine, riprova.");
        };


        reader.readAsDataURL(imageFile);
    });

    //console.log("Finito foto");
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
            if (data.success) {

                //window.sessionData.username = data.email;
                if (document.body.id == "root-account") {
                    window.sessionData.nome = data.dati["name"];
                    window.sessionData.cognome = data.dati["surname"];
                    window.sessionData.ruolo = data.dati["role"];
                    document.getElementById("titolo-nome-utente").textContent = data.dati["name"] + " " + data.dati["surname"];
                }

                

                datiCorrenti = data.dati;
                console.log(datiCorrenti);
                if (datiCorrenti["photo"]!="" && datiCorrenti["photo"] !== null) {
                    imagePreview.src = "../immagini/foto_profilo/"+datiCorrenti["photo"];
                } else {
                    imagePreview.src = "../immagini/default_profile.webp";
                }

                for (let i = 0; i < fields.length; i++) {
                    fields[i].value = datiCorrenti[fields[i].id];
                    //console.log(data.dati[fields[i].id]);
                }

                for (i = 0; i<roles.length; i++) {
                    //console.log(roles[i]);

                    if (roles[i].id == datiCorrenti["role"]) {
                        roles[i].checked = true;
                        roles[i].disabled = false;
                    } else {
                        roles[i].disabled = true;
                    }
                    
                }
                
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Errore:' + error));

        console.log("Finito campi");
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
    document.getElementById("photo").style.display = "none";
    document.getElementById("photo").value = "";
    document.getElementById("image-preview").scr = datiCorrenti["photo"];
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

    if (ROLE=="" && document.body.id=="root-account") {
        alert("Il campo ruolo non può essere vuoto.");
        return;
    }
    
    fetchBody.append("role", ROLE);

    if(!confirm(confMess)) {return;}

    if(photoInput.files.length!==0) {
        fetchBody.append("photo", imageFile);
    }

    let check = checkDati(fetchBody);
    if(!check.ok) {alert(check.msg);return;}
    
    fetch(APIurl, {
      method: "POST",
      body: fetchBody
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        if (data.success) {
            alert(data.message);
            window.location.href = "../frontend/login.php";
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
        //console.log("pass ", fetchBody.get("password"));
    }

    //console.log(datiNuovi["DOB"]);

    for (let i = 0; i < fields.length; i++) {
        if (datiNuovi[fields[i].id] == "") {
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

    if(logoutBool) {
        confMess += " Stai modificando dati sensibili. Verrà effettuato il logout";
    }
    
    let ROLE = "";
    for (i = 0; i<roles.length; i++) {

        if (roles[i].checked) {
            ROLE = roles[i].value;
        }
        
    }

    if (ROLE=="" && document.body.id=="root-account") {
        alert("Il campo ruolo non può essere vuoto.");
        return;
    }
    
    if (ROLE != datiCorrenti["role"] && document.body.id=="root-account") {
        fetchBody.append("role", ROLE);
    }

    if(!confirm(confMess)) {return;}

    if(photoInput.files.length!==0) {
        fetchBody.append("photo", imageFile);
        //fetchBody.append("photoname", datiCorrenti["photo"]);
    }

    let check = checkDati(fetchBody);
    if(!check.ok) {alert(check.msg);return;}
    
    fetch(APIurl, {
      method: "POST",
      body: fetchBody
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        if (data.success) {
            alert(data.message);
            if(logoutBool) {
                logout();
                return;
            } else {
                aggiorna_info();
            }
        } else {
            alert(data.message);
            //logout();
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
    document.getElementById("photo").style.display = "inline-block";
    document.getElementById("password-conf").readOnly = false;
    document.getElementById("password-conf").style.display = "inline-block";
    document.getElementById("account-data-enable").style.display = "none";
    document.getElementById("account-data-erase").style.display = "inline-block";
    document.getElementById("account-data-cancel").style.display = "inline-block";
    document.getElementById("account-data-submit").style.display = "inline-block";
    
}

function checkDati(data) {

    /*
    --mail formato corretto
    --pass con caratteri: lettere, numeri, @, *, $
    --Nome e cognome solo lettere, 1 <lunghezza < 100
    --DOB esistente e passata
    --Ruolo modificabile solo se root
    Ruolo tra quelli consentiti
    */

    let okk = true; let mess = ""; let errcount = 0;

    //Check pass
    if(data.has("password")) {

        let pw = data.get("password");
        if (pw.length < 3 || pw.length > 255) {okk = false; errcount++;
            mess += "La lunghezza della password deve essere tra 3 e 255 caratteri\n";
        }

        if (!(/^[a-zA-Z0-9@-_]+$/.test(pw))) {okk = false; errcount++;
            mess += "La password può contenere solo lettere ASCII, numeri, @, -, _\n";
        }
    }

    //Check mail
    if(data.has("email")) {

        let em = data.get("email");
        if (!(/^[a-zA-Z][\w]*.[\w]+@[\w]+.[a-zA-Z]+$/.test(em))) {okk = false; errcount++;
            mess += "L'email non è nel formato corretto o ha caratteri vietati\n";
        }
    }

    //Check DOB
    if(data.has("DOB")) {

        let date = new Date(data.get("DOB"));
    
        if (Object.prototype.toString.call(date) === "[object Date]") {
            if (isNaN(date.getTime())) {okk = false; errcount++;
                mess += "Data di nascita non valida\n";
            }

            let today = new Date();
            today.setHours(0, 0, 0, 0);
            if (date >= today) {okk = false; errcount++;
                mess += "Data di nascita deve essere nel passato\n";
            }
        } else {okk = false; errcount++;
            mess += "Data di nascita non valida\n";
        }
    }

    //Check nome
    if(data.has("name")) {

        let nomi = data.get("name").trim().split(/\s+/);
        for (nm of nomi) {
            console.log(nm);
            if (!(/^\p{L}+$/u.test(nm))) {okk = false; errcount++;
                mess += "Il nome può contenere solo lettere\n"; break;
            }

            if (nm.length > 100) {okk = false; errcount++;
                mess += "Il nome deve avere massimo 100 lettere\n"; break;
            }

            if (nm.length < 1) {okk = false; errcount++;
                mess += "Il nome deve avere almeno una lettera\n"; break;
            }
        }

        data.set("name", nomi.join(" "));
        console.log(data.get("name"));
    }

    //Check cognome
    if(data.has("surname")) {

        let cognomi = data.get("surname").trim().split(/\s+/);
        for (cnm of cognomi) {
            console.log(cnm);
            if (!(/^\p{L}+$/u.test(cnm))) {okk = false; errcount++;
                mess += "Il cognome può contenere solo lettere\n"; break;
            }

            if (cnm.length > 100) {okk = false; errcount++;
                mess += "Il cognome deve avere massimo 100 lettere\n"; break;
            }

            if (cnm.length < 1) {okk = false; errcount++;
                mess += "Il cognome deve avere almeno una lettera\n"; break;
            }
        }

        data.set("surname", cognomi.join(" "));
        console.log(data.get("surname"));
    }

    //Check ruolo
    if(data.has("role") && document.body.id=="root-account") {

        let rl = data.get("role");
        if (!(rl in ["studente", "tecnico", "docente"])) {okk = false; errcount++;
            mess += "Ruolo non consentito\n";
        }
    }

    mess = "Ci sono "+errcount+" errori nei dati:\n"+mess

    return {ok: okk, msg: mess};

}
