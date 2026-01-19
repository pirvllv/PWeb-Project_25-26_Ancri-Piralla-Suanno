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

function mostraFormResponsabile() {
    const Responsabili = document.getElementById('form-email-responsabile');

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
    listaSettori.forEach(s => {
        const nome = s.ResponsabileNome ?? "—";
        const cognome = s.ResponsabileCognome ?? "—";
        const email = s.ResponsabileEmail ?? "—";

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
                        <button class='green-button' style='padding: 8px 16px; white-space: nowrap;' onclick="mostraFormResponsabile()">Modifica</button>
                        <div>
                            <form id='form-email-responsabile' style='display:none;'>
                                <select id='email-resp' class='form-control' required>
                                    <option value="NULL">Nessun responsabile</option>
                                </select>
                                <button class='green-button' type='submit' onclick="mostraFormResponsabile()">Modifica</button>
                                <button class='red-button' type='reset' onclick="mostraFormResponsabile()">Annulla</button> 
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        `;
    })

    container.innerHTML = html;
}

document.addEventListener('DOMContentLoaded', function() {
    caricaSettori();
})