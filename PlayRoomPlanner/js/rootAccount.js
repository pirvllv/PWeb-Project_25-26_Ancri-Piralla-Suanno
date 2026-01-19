function mostraSezione(sectionId) {
    const Responsabili = document.getElementById('manager-responsabili');
    const DatiUtenti = document.getElementById('edit-user-data');
    const Prenotazioni = document.getElementById('booking-manager');
    
    switch (sectionId) {
        case 'manager-responsabili':
            if (Responsabili) Responsabili.style.display = 'block';
            if (DatiUtenti) DatiUtenti.style.display = 'none';
            if (Prenotazioni) Prenotazioni.style.display = 'none';
            break;
        case 'edit-user-data':
            if (DatiUtenti) DatiUtenti.style.display = 'block';
            if (Responsabili) Responsabili.style.display = 'none';
            if (Prenotazioni) Prenotazioni.style.display = 'none';
            break;
        case 'booking-manager':
            if (Prenotazioni) Prenotazioni.style.display = 'block';
            if (Responsabili) Responsabili.style.display = 'none';
            if (DatiUtenti) DatiUtenti.style.display = 'none';
            break;
        default:
            if (Responsabili) Responsabili.style.display = 'none';
            if (DatiUtenti) DatiUtenti.style.display = 'none';
            if (Prenotazioni) Prenotazioni.style.display = 'none';
            break;
    }
}

function mostraFormResponsabile(formId) {
    const Responsabili = document.getElementById(formId);

    Responsabili.style.display == 'none' ? Responsabili.style.display = 'block' : Responsabili.style.display = 'none';
}

function caricaSettori() {
    fetch('/PlayRoomPlanner/backend/api-rootAccount.php?azione=mostraSettori')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostraSettori(data.data);
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Errore: ', error));
}

function mostraSettori(listaSettori) {
    const container = document.getElementById('lista-responsabili');

    let html = '';
    listaSettori.forEach((s, index) => {
        const nome = s.ResponsabileNome ?? "—";
        const cognome = s.ResponsabileCognome ?? "—";
        const email = s.ResponsabileEmail ?? "—";

        const formId = `form-email-responsabile-${index}`;
        const selectId = `email-resp-${index}`;

        html += `
            <div style='margin-bottom: 15px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; background-color: #fafafa;'>
                <div style='display: flex; justify-content: space-between; align-items: flex-start;'>
                    <div style='flex: 1;'>
                        <div style='margin-bottom: 8px;'>
                            <strong>Settore:</strong> ${s.SettoreNome}
                        </div>
                        <div style='margin-bottom: 8px;'>
                            <strong>Nome:</strong> ${nome}
                        </div>
                        <div style='margin-bottom: 8px;'>
                            <strong>Cognome:</strong> ${cognome}
                        </div>
                        <div style='margin-bottom: 8px;'>
                            <strong>Email:</strong> ${email}
                        </div>

                        <button class='green-button'
                                style='padding: 8px 16px; white-space: nowrap;'
                                onclick="mostraFormResponsabile('${formId}'); caricaResponsabili('${selectId}')">
                            Modifica
                        </button>

                        <div>
                            <form id='${formId}' data-settore='${s.SettoreNome}' style='display:none;'>

                                <select id='${selectId}' class='form-control' required>
                                    <option value="NULL">Nessun responsabile</option>
                                </select>

                                <button class='green-button' type='submit' onclick="mostraFormResponsabile('${formId}')">Modifica</button>
                                <button class='red-button' type='reset'
                                        onclick="mostraFormResponsabile('${formId}')">
                                    Annulla
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });

    container.innerHTML = html;
}


function caricaResponsabili(selectId) {
    fetch('/PlayRoomPlanner/backend/api-rootAccount.php?azione=caricaResponsabili')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                visualizzaResponsabili(data.data, selectId);
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Errore: ', error));
}

function visualizzaResponsabili(responsabili, selectId) {
    const select = document.getElementById(selectId);
    if (!select) return; // sicurezza

    let optionsHtml = '<option value="NULL">Nessun responsabile</option>';

    responsabili.forEach(r => {
        optionsHtml += `
            <option value="${r.DocenteEmail}">
                ${r.DocenteNome} ${r.DocenteCognome}
            </option>
        `;
    });

    select.innerHTML = optionsHtml;
}

function aggiornaResponsabile(settore, email) {
    const formData = new FormData();
    formData.append('azione', 'aggiornaResponsabile');
    formData.append('settore', settore);
    formData.append('email', email);
    fetch('/PlayRoomPlanner/backend/api-rootAccount.php', {
        method: "POST",
        body: formData
        })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Responsabile aggiornato");
            caricaSettori();
        } else {
            alert(data.message);
        }
    })
    .catch(err => console.error("Errore:", err));
}


document.addEventListener('DOMContentLoaded', function() {
    caricaSettori();

    document.getElementById("lista-responsabili").addEventListener("submit", function(e) {
        if (e.target.matches("form[id^='form-email-responsabile-']")) {
            e.preventDefault();

            const form = e.target;
            const settore = form.dataset.settore;
            const select = form.querySelector("select");
            const email = select.value;

            aggiornaResponsabile(settore, email);
        }
    });

})