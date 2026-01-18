document.addEventListener("DOMContentLoaded", function() {

    todayStamp = Date.now()/1000;
    weekOffset = 0;
    rossiShown = false;
    APIurl = "../backend/bookings_API.php?";
    primkey="";
    hmax = 18;

    if (document.body.id=="area-personale") {

        primkey = window.sessionData.username;
        showBookings(todayStamp, "invites");
        changeWeek(0, "week"); 
        
    }

    if (document.body.id=="prenotazioni-aula") {

        primkey = window.sessionData.aula;
        showBookings(todayStamp, "room");
        changeWeek(0, "room");
        
    }
    
}); 

function table_from_schedule(sched) {

    let table = "";
    let hmin = 9;
    for (const g in sched) {
        
        for (const i in sched[g]) {

            //console.log("g: "+g+", i: "+i);
            
            let att = sched[g][i];
            
            if (att["orafine"]<=att["orainizio"]) {continue;}

            let column = parseInt(g)+2;
            let row = 2*(att["orainizio"]/3600-hmin+1)+1;
            let span = (att["orafine"]-att["orainizio"])/1800;
            //console.log("row: "+row);
            //console.log("col: "+column);
            //console.log("span: "+span);
            
            table += "\n<div id='"+att["IDP"]+"' class=\"cell att " + att["stato"];
            table += "\" style=\"grid-area: " + row + "/" + column + "/ span " + span + "/" + column + ";\">";
            table += att["attivita"];
            table += "<span class='invites-icons'>";
            table += '<button id="'+att["IDP"]+'" class="dclnBtn" onclick="gestisciInvito(this,3)"><i class="bi bi-x-circle-fill declineInv"></i></button>';
            //table += '<button class="btn dclnBtn"><i class="bi bi-x-circle-fill declineInv"></i></button>';
            table += "</span></div>";

        }

    }

    //console.log(table);
    document.getElementById("timetbl").innerHTML += table;

    let sera = document.getElementsByClassName("hsera");
    for (let i = 0; i < sera.length; i++) {
        console.log(hmax, 18*3600);
        sera[i].style.display = (hmax>18*3600)?"block":"none";
    }

}

function setWeekdays(week) {
    
    for (const d in week) {

        //console.log(week[d][0]);
        let days = document.getElementsByClassName(week[d][0]);
        for (let i = 0; i < days.length; i++) {
            days[i].innerHTML = week[d][0] + " " + week[d][1];
        }
        
    }
    
}

function showBookings(oggi, type) {

    let weekName = document.getElementById("weekname");
    
    //let monday = Date.now()/1000;
    let url = APIurl+"today="+oggi;
    url += "&primkey="+primkey;
    url += "&type="+type;
    //console.log(url);
    fetch(url)
        .then(response => response.json())
        .then(data => {
            //console.log(data);
            //console.log("data arrived - "+type);
            if (data.success) {
                let html = data.dati.weekstart;
                weekName.innerHTML = weekOffset==0?"Questa settimana":("Settimana del "+html);
                
                if (type!="invites") {
                    clearTable();
                    hmax = data.dati.hmax;
                    table_from_schedule(data.dati.bookings);
                    setWeekdays(data.dati.week);
                } else {
                    clearScroll();
                    scroll_from_invites(data.dati.bookings);
                    //check_vuoti();
                    //console.log(data.dati.bookings);
                }
                
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Errore:' + error));
    
    
    
}

function displayAtt(att) {

    let out = "";
    //console.log(att);
    if (att["orafine"]<=att["orainizio"]) {return "";}
    
    out += "<div id='"+att["IDP"]+"' class=\"cell " + att["stato"]+ " att\" ";
    if(att["stato"]==="attRifiutata") {
        out += "style='display: none;'";
    }
    out += ">";
    out += att["attivita"];
    out += "<span class='invites-icons'>";
    out += '<button class="axptBtn" onclick="gestisciInvito(this,'+(att['stato']=='attInSospeso'?0:2)+')">';
    //out += '<button class="btn axptBtn">';
    out += '<i class="bi bi-check-circle-fill acceptInv"></i></button>';
    if(att["stato"]=="attInSospeso") {
        out += '<button class="dclnBtn" onclick="gestisciInvito(this, 1)">';
        //out += '<button class="btn dclnBtn">';
        out+= '<i class="bi bi-x-circle-fill declineInv"></i></button>';
    }
    out += "</span></div>";

    //console.log("Att: "+out)
    return out;
    
}

function scroll_from_invites(inviti) {

    //console.log(week);
    //console.log(inviti);
    let scroll = document.getElementById("scroll");
    //console.log(scroll);
    let scrollhtml = "";
    //console.log(inviti);

    for (const g in inviti) {
        //console.log(week[g]);
        let dayconthtml = "<div id='"+ g +"' class='daycont'>";
        let invithtml = "<div class=\"cell index\">"+inviti[g]["wkday"]+"</div>";
        for (const attIdx in inviti[g]["attivita"]) {
            invithtml += displayAtt(inviti[g]["attivita"][attIdx]);
            //console.log("Attività aggiunta");
        }
        dayconthtml += invithtml + "</div>";
        scrollhtml += dayconthtml;
        //console.log(scroll.innerHTML);

    }
    scroll.innerHTML += scrollhtml;
    toggle_rossi(rossiShown);
    //console.log("Invites fine");
    //console.log(scroll.innerHTML);
    return;
    
}

function changeWeek(amt, type) {

    weekOffset += amt;
    //console.log("offset: "+weekOffset);
    let newToday = todayStamp + (3600*24*7*weekOffset);
    showBookings(newToday, type);
    return;
    
}

function clearTable() {

    //console.log("cleartable");

    let activities = document.getElementsByClassName("attAccettata");
    //console.log(activities);
    while(activities.length != 0) {
        //console.log(i);
        activities[0].remove();
        
    }
}

function clearScroll() {

    let conts = document.getElementsByClassName("daycont");
    while(conts.length != 0) {
        conts[0].remove();
        
    }
}

function toggle_rossi(shown) {

    if (arguments.length === 0) {
        rossiShown = !rossiShown;
    } else {
        rossiShown = shown;
    }

    let butt = document.getElementById("toggle-red");
    let reds = document.getElementsByClassName("attRifiutata");
    
    if(rossiShown) {
        butt.classList.add("rossi-shown");
    } else {
        butt.classList.remove("rossi-shown");
    }

    let disp = rossiShown?"block":"none";
    for (let i = 0; i < reds.length; i++) {
        reds[i].style.display = disp;
    }
    
    check_vuoti();
    //console.log("fine rossi");

}

function check_vuoti() {

    let dayconts = document.getElementsByClassName("daycont");
    //console.log(dayconts.length);
    let countDay = dayconts.length;
    for (let i = 0; i < dayconts.length; i++) {
        let countAct = 0;
        let children = dayconts[i].children;
        
        for (let k = 0; k < children.length; k++) {
            
            if (!children[k].classList.contains("index") && children[k].style.display != "none") {countAct++;}
            //console.log(children[k]);
            
        }
        //console.log(dayconts[i].firstChild.textContent+ " - "+count);
        if (countAct<1) {
            dayconts[i].style.display = "none";
            countDay--;
        }
        else {
            dayconts[i].style.display = "block";
        }
    }

    if (countDay==0) {
        document.getElementById("no-invites").style.display= "block";
    } else {
        document.getElementById("no-invites").style.display= "none";
    }

    //console.log("Check fine");

}

function gestisciInvito(btnEl, code) {

    //0: accetta da sospeso
    //1: rifiuta da sospeso
    //2: accetta da rifiutato
    //3: rifiuta da accettato

    let name = btnEl.parentElement.parentElement.textContent;
    let IDP = btnEl.parentElement.parentElement.id;

    let msg = "";
    let act = -1;
    switch(code) {
        case 0: msg = "Vuoi davvero accettare l'invito a " + name + "?"; act = 1; break;
        case 1: msg = "Vuoi davvero rifiutare l'invito a " + name + "? Dai una motivazione:"; act = 0; break;
        case 2: msg = "Avevi rifiutato l'invito a " + name + ". Sei sicuro di voler cambiare?"; act = 1; break;
        case 3: msg = "Vuoi davvero annullare la tua partecipazione a " + name + "? Dai una motivazione:"; act = 0; break;
    }
    if(msg=="" || act==-1) {alert("Errore nel codice di accettazione/rifiuto inviti. Contatta un tecnico"); return;}
    
    let url = APIurl+"primkey="+IDP;
    url += "&type=change";
    url += "&action="+act;

    if (act==0) {
        let just = prompt(msg);
        while (just === "") {
            just = prompt("La motivazione non può essere vuota:");
        }
        if (just===null) {return;}
        console.log(just);
        url += "&just="+encodeURI(just);
    } else {
        if(!confirm(msg)) {return;}
    }

    //console.log(url);
    fetch(url)
        .then(response => response.json())
        .then(data => {
            //console.log(data);
            //console.log("ciao");
            if (data.success) {
                //alert(data.message);
                //document.getElementById("scroll").innerHTML = "";
                if (act==0) {
                    showBookings(todayStamp, "invites");
                }
                else {document.getElementById(IDP).remove();}
                changeWeek(0, "week");
                toggle_rossi(rossiShown);
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Errore:' + error));

}