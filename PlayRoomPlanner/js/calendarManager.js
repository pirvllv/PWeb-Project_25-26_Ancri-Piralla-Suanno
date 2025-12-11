document.addEventListener("DOMContentLoaded", function() {
    const weekName = document.getElementById("weekname");
    const timetable = document.getElementById("timetbl");
    //let monday = Date.now()/1000;
    let monday = new Date("2025-11-11").getTime()/1000;
    let url = "../backend/bookings_API.php?";
    url += "monday="+monday;
    url += "&primkey="+document.getElementById("roomname").innerHTML;
    url += "&type="+"room";
    console.log(url);
    fetch(url)
        .then(response => response.json())
        .then(data => {
            console.log(data);
            //console.log("ciao");
            if (data.success) {
                let html = data.dati.weekstart;
                weekName.innerHTML = html;
                timetable.innerHTML += table_from_schedule(data.dati.sched, 8, 18);
                setWeekdays(data.dati.week);
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Errore:', error));
}); 

function table_from_schedule(sched, hmin, hmax) {

    let table = "";
    for (const g in sched) {
        
        for (const i in sched[g]) {

            console.log("g: "+g+", i: "+i);
            
            let att = sched[g][i];
            
            if (att["orafine"]<=att["orainizio"]) {continue;}

            let column = parseInt(g)+2;
            let row = 2*(att["orainizio"]/3600-hmin+1)+1;
            let span = (att["orafine"]-att["orainizio"])/1800;
            console.log("row: "+row);
            console.log("col: "+column);
            console.log("span: "+span);
            
            table += "\n<div class=\"cell " + att["stato"];
            table += "\" style=\"grid-area: " + row + "/" + column + "/ span " + span + "/" + column + ";\">";
            table += att["attivita"] + "</div>";

        }

    }

    console.log(table);
    return table;

}

function setWeekdays(week) {
    
    for (const d in week) {

        let day = document.getElementById(d);
        day.innerHTML = week[d];
        
    }
    
}