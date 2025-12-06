document.addEventListener("DOMContentLoaded", () => {
    fetchPrenotazioni();
});

function fetchPrenotazioni() {
    const formData = new FormData();
    formData.append("azione", "mostraPren");

    fetch("../backend/api-gestionePrenotazioni.php", {
        method: "POST",
        body: formData
    })
    .then(r => r.text())
    .then(html => {
        const div = document.getElementById("lista-prenotazioni");
        div.innerHTML = html;

        document.querySelectorAll(".pren-item").forEach(item => {
            item.addEventListener("click", () => {
                selezionaPrenotazione(item);
            });
        });
    })
    .catch(() => {
        document.getElementById("lista-prenotazioni").textContent = "Errore nel caricamento.";
    });
}

function selezionaPrenotazione(item) {
    const id = item.dataset.id;

    document.getElementById("id-mod").value = id;
    document.getElementById("id-del").value = id;

    document.getElementById("info-pren").textContent =
        `Prenotazione ${id}: ${item.dataset.data} ${item.dataset.oraInizio}-${item.dataset.oraFine}, Aula ${item.dataset.aula}`;

    document.getElementById("azioni-prenotazione").style.display = "block";
}
