function getMonday(d) {
    d = new Date(d);
    const day = d.getDay();
    const diff = d.getDate() - (day === 0 ? 6 : day - 1); 
    return new Date(d.setDate(diff));
}

let currentMonday = getMonday(new Date());

function renderWeek() {
    const daysList = document.getElementById("settimana");
    const label = document.getElementById("labelSett");

    daysList.innerHTML = "";

    const start = new Date(currentMonday);
    const end = new Date(currentMonday);
    end.setDate(end.getDate() + 6);

    label.textContent = `${start.toLocaleDateString()} - ${end.toLocaleDateString()}`;

    for (let i = 0; i < 7; i++) {
        const d = new Date(currentMonday);
        d.setDate(d.getDate() + i);
        daysList.innerHTML += `
            <li>
                <button class="giorno-btn" data-date="&{d.toISOString()}">
                    ${d.getDate()}
                </button>
            </li>`;
    }
}

document.getElementById("settPrec").onclick = () => {
    currentMonday.setDate(currentMonday.getDate() - 7);
    renderWeek();
};

document.getElementById("settSucc").onclick = () => {
    currentMonday.setDate(currentMonday.getDate() + 7);
    renderWeek();
};

renderWeek();
