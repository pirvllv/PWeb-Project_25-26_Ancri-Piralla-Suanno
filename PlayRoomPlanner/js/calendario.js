function getMonday(d) {
    d = new Date(d);
    const day = d.getDay();
    const diff = d.getDate() - (day === 0 ? 6 : day - 1); 
    return new Date(d.setDate(diff));
}

let currentMonday = getMonday(new Date());

function renderWeek() {
    const daysList = document.getElementById("settimana-value");
    const label = document.getElementById("label-sett");

    daysList.innerHTML = "";
    daysList.classList.add("grid-settimana");

    const start = new Date(currentMonday);
    const end = new Date(currentMonday);
    end.setDate(end.getDate() + 6);

    label.textContent = `${start.toLocaleDateString()} - ${end.toLocaleDateString()}`;

    for (let i = 0; i < 7; i++) {
        const d = new Date(currentMonday);
        d.setDate(d.getDate() + i);
        daysList.innerHTML += `
            <li>
                <button class="green-button" data-date="${d.toISOString()}">
                    ${d.getDate()}
                </button>
            </li>`;
    }
}

document.getElementById("btn-sett-prec").onclick = () => {
    currentMonday.setDate(currentMonday.getDate() - 7);
    renderWeek();
};

document.getElementById("btn-sett-succ").onclick = () => {
    currentMonday.setDate(currentMonday.getDate() + 7);
    renderWeek();
};

renderWeek();
