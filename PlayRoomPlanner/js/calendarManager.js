document.addEventListener("DOMContentLoaded", function() {

    todayStamp = new Date("2025-11-11").getTime()/1000;
    weekOffset = 0;
    APIurl = "../backend/bookings_API.php?";
    primkey="";
    type="";
    if (document.body.id=="area-personale") {

        primkey = document.getElementById("username").innerHTML;
        type = "invites";
        showBookings(todayStamp);
        changeWeek(0);
        
    }

    if (document.body.id=="prenotazioni-aula") {

        primkey = document.getElementById("roomname").innerHTML;
        type = "room";
        showBookings(todayStamp);
        
    }
    
}); 

function table_from_schedule(sched, hmin, hmax) {

    let table = "";
    for (const g in sched) {
        
        for (const i in sched[g]) {

            ////console.log("g: "+g+", i: "+i);
            
            let att = sched[g][i];
            
            if (att["orafine"]<=att["orainizio"]) {continue;}

            let column = parseInt(g)+2;
            let row = 2*(att["orainizio"]/3600-hmin+1)+1;
            let span = (att["orafine"]-att["orainizio"])/1800;
            ////console.log("row: "+row);
            ////console.log("col: "+column);
            ////console.log("span: "+span);
            
            table += "\n<div class=\"cell att " + att["stato"];
            table += "\" style=\"grid-area: " + row + "/" + column + "/ span " + span + "/" + column + ";\">";
            table += att["attivita"] + "</div>";

        }

    }

    //console.log(table);
    return table;

}

function setWeekdays(week) {
    
    for (const d in week) {

        let days = document.getElementsByClassName(d);
        for (let i = 0; i < days.length; i++) {
            days[i].innerHTML = d + " " + week[d];
        }
        
    }
    
}

function showBookings(oggi) {

    const weekName = document.getElementById("weekname");
    const timetable = document.getElementById("timetbl");
    //let monday = Date.now()/1000;
    let url = APIurl+"today="+oggi;
    url += "&primkey="+primkey;
    url += "&type="+type;
    console.log(url);
    fetch(url)
        .then(response => response.json())
        .then(data => {
            //console.log(data);
            //console.log("ciao");
            if (data.success) {
                let html = data.dati.weekstart;
                weekName.innerHTML = weekOffset==0?"corrente":("del "+html);
                clearTable();
                //console.log(data.dati.sched);
                if (type!="invites") {
                    timetable.innerHTML += table_from_schedule(data.dati.bookings, 8, 18);
                } else {
                    scroll_from_invites(data.dati.bookings, data.dati.week);
                }
                setWeekdays(data.dati.week);
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Errore:' + error));
    
}

function displayAtt(att) {

    let out = "";
    if (att["orafine"]<=att["orainizio"]) {return "";}
    
    out += "<div class=\"cell att " + att["stato"];
    //table += "\" style=\"grid-area: " + row + "/" + column + "/ span " + span + "/" + column + ";\">";
    out += att["attivita"] + "</div>";
    
}

function scroll_from_invites(inviti, week) {

    for (const g in inviti) {
        let daycont = getElementById(week[g]+"-cont");
        daycont.innerHTML += "<div class=\"cell index\" id=\""+week[g]+"\"></div>";
        for (const att in inviti[g]) {
            daycont.innerHTML += displayAtt(att);
        }
        daycont.innerHTML += "<br>";

    }

    return scroll;
    
}

function changeWeek(amt) {

    weekOffset += amt;
    //console.log("offset: "+weekOffset);
    let newToday = todayStamp + (3600*24*7*weekOffset);
    type = "week";
    showBookings(newToday);
    return;
    
}

function clearTable() {

    let activities = document.getElementsByClassName("att");
    for (let i = 0; i < activities.length; i++) {
        //activities[i].style.backgroundColor = "red";
        activities[i].remove();
    }
}