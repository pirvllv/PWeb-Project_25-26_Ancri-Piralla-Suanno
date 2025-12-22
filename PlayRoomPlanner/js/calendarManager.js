document.addEventListener("DOMContentLoaded", function() {

    todayStamp = Date.now()/1000;
    weekOffset = 0;
    APIurl = "../backend/bookings_API.php?";
    primkey="";
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

function table_from_schedule(sched, hmin, hmax) {

    let table = "";
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

        //console.log(week[d][0]);
        let days = document.getElementsByClassName(week[d][0]);
        for (let i = 0; i < days.length; i++) {
            days[i].innerHTML = week[d][0] + " " + week[d][1];
        }
        
    }
    
}

function showBookings(oggi, type) {

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
                weekName.innerHTML = weekOffset==0?"Questa settimana":("Settimana del "+html);
                
                if (type!="invites") {
                    clearTable();
                    timetable.innerHTML += table_from_schedule(data.dati.bookings, 8, 18);
                    setWeekdays(data.dati.week);
                    
                } else {
                    scroll_from_invites(data.dati.bookings);
                    console.log(data.dati.bookings);
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
    
    out += "<div class=\"cell " + att["stato"] + "\">";
    out += att["attivita"] + "</div>";

    //console.log("Att: "+out)
    return out;
    
}

function scroll_from_invites(inviti) {

    //console.log(week);
    //console.log(inviti);
    let scroll = document.getElementById("scroll");
    for (const g in inviti) {
        //console.log(week[g]);
        let daycont = document.createElement('div');
        daycont.setAttribute("id", g);
        daycont.innerHTML += "<div class=\"cell index\">"+inviti[g]["wkday"]+"</div>";
        for (const attIdx in inviti[g]["attivita"]) {
            daycont.innerHTML += displayAtt(inviti[g]["attivita"][attIdx]);
        }
        scroll.appendChild(daycont);
        //daycont.innerHTML += "<br>";

    }
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

    let activities = document.getElementsByClassName("att");
    //console.log(activities);
    while(activities.length != 0) {
        //console.log(i);
        activities[0].remove();
        
    }
}