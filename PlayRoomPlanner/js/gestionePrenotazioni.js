/* Apre la form richiesta e chiude quella precedentemente aperta, o le chiude tutte se richiesto */
function mostraForm(formId) {
    const creaForm = document.getElementById('crea');
    const modificaForm = document.getElementById('modifica');
    const invitaForm = document.getElementById('invita');
    
    switch (formId) {
        case 'crea':
            if (creaForm) creaForm.style.display = creaForm.style.display === 'none' ? 'block' : 'none';
            if (modificaForm) modificaForm.style.display = 'none';
            if (invitaForm) invitaForm.style.display = 'none';
            break;
        case 'modifica':
            if (modificaForm) modificaForm.style.display = 'block';
            if (creaForm) creaForm.style.display = 'none';
            if (invitaForm) invitaForm.style.display = 'none';
            break;
        case 'invita':
            if (invitaForm) invitaForm.style.display = 'block';
            if (creaForm) creaForm.style.display = 'none';
            if (modificaForm) modificaForm.style.display = 'none';
            break;
        default:
            if (creaForm) creaForm.style.display = 'none';
            if (modificaForm) modificaForm.style.display = 'none';
            if (invitaForm) invitaForm.style.display = 'none';
            break;
    }
}

/* Ottiene la lista di tutte le aule facenti riferimento al macrosettore del responsabile loggato */
function caricaAule() {
    fetch('/PlayRoomPlanner/backend/api-gestionePrenotazioni.php?azione=getAule')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                visualizzaAule(data.data);
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Errore:', error));
}

/* Ottiene la lista di tutte le prenotazioni a nome del responsabile loggato */
function caricaPrenotazioni() {
    fetch('/PlayRoomPlanner/backend/api-gestionePrenotazioni.php?azione=mostraPren')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                visualizzaPrenotazioni(data.data);
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Errore:', error));
}

/* Visualizza la lista di tutte le prenotazioni a nome del responsabile loggato */
function visualizzaPrenotazioni(prenotazioni) {
    const container = document.getElementById('lista-prenotazioni');
    
    if (prenotazioni.length === 0) {
        container.innerHTML = '<p>Nessuna prenotazione trovata.</p>';
        return;
    }

    let html = '';
    prenotazioni.forEach(p => {
        const oraInizio = p.OraInizio.substring(0, 5);
        const oraFine = p.OraFine.substring(0, 5);
        
        html += `
            <div style='margin-bottom: 15px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; background-color: #fafafa;'>
                <div style='display: flex; justify-content: space-between; align-items: flex-start;'>
                    <div style='flex: 1;'>
                        <div style='margin-bottom: 8px;'>
                            <strong>ID Prenotazione:</strong> ${p.IDPrenotazione}
                        </div>
                        <div style='margin-bottom: 8px;'>
                            <strong>Data:</strong> ${p.DataPren}
                        </div>
                        <div style='margin-bottom: 8px;'>
                            <strong>Orario:</strong> ${oraInizio} - ${oraFine}
                        </div>
                        <div style='margin-bottom: 8px;'>
                            <strong>Aula:</strong> ${p.NumAula}
                        </div>
                        <div style='margin-bottom: 8px;'>
                            <strong>Attività:</strong> ${p.Attivita}
                        </div>
                    </div>
                    <div style='display: flex; gap: 8px; flex-direction: column;'>
                        <button class='green-button' style='padding: 8px 16px; white-space: nowrap;' onclick="mostraForm('invita'); caricaInviti(${p.IDPrenotazione})">Invita</button>
                        <button class='orange-button' style='padding: 8px 16px; white-space: nowrap;' onclick="caricaModifica(${p.IDPrenotazione}, '${p.DataPren}', '${p.OraInizio}', '${p.OraFine}', '${p.NumAula}', '${p.Attivita}')">Modifica</button>
                        <button class='red-button' style='padding: 8px 16px; white-space: nowrap;' onclick="eliminaPrenotazione(${p.IDPrenotazione})">Elimina</button>
                    </div>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

/* Aggiunge alle select delle form di creazione e modifica solo le aule prenotabili dal responsabile */
function visualizzaAule(aule) {
    const selectCrea = document.getElementById('crea-aula');
    const selectModifica = document.getElementById('modifica-aula');
    
    const valoreCreaCorrente = selectCrea ? selectCrea.value : '';
    const valoreModificaCorrente = selectModifica ? selectModifica.value : '';
    
    let optionsHtml = '<option value="">Seleziona aula</option>';
    aule.forEach(aula => {
        optionsHtml += `<option value="${aula.NumAula}">${aula.NumAula} (${aula.SettoreNome} - Capienza: ${aula.Capienza})</option>`;
    });
    
    if (selectCrea) {
        selectCrea.innerHTML = optionsHtml;
        selectCrea.value = valoreCreaCorrente;
    }
    if (selectModifica) {
        selectModifica.innerHTML = optionsHtml;
        selectModifica.value = valoreModificaCorrente;
    }
}

/* Invia form di creazione e modifica */
function inviaForm(form, azione) {
    const formData = new FormData(form);
    formData.append('azione', azione);

    fetch('/PlayRoomPlanner/backend/api-gestionePrenotazioni.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.success) {
            form.reset();
            mostraForm('crea');
            caricaPrenotazioni();
        }
    })
    .catch(error => console.error('Errore:', error));
}

/* Mostra i dati attuali di una prenotazione nel form di modifica */
function caricaModifica(id, data, oraInizio, oraFine, aula, attivita) {
    const oraInizioFormattata = oraInizio.substring(0, 5);
    const oraFineFormattata = oraFine.substring(0, 5);
    
    document.getElementById('modifica-id').value = id;
    document.getElementById('modifica-data').value = data;
    document.getElementById('modifica-oraInizio').value = oraInizioFormattata;
    document.getElementById('modifica-oraFine').value = oraFineFormattata;
    document.getElementById('modifica-aula').value = aula;
    document.getElementById('modifica-attivita').value = attivita;
    document.getElementById('modifica-testo-dettagli').textContent = `ID: ${id} | Data: ${data} | Orario: ${oraInizioFormattata}-${oraFineFormattata} | Aula: ${aula} | Attività: ${attivita}`;
    mostraForm('modifica');
}

/* Elimina una prenotazione */
function eliminaPrenotazione(id) {
    if (!confirm('Sei sicuro di voler eliminare questa prenotazione?')) return;

    const formData = new FormData();
    formData.append('azione', 'elimina');
    formData.append('IDPrenotazione', id);

    fetch('/PlayRoomPlanner/backend/api-gestionePrenotazioni.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.success) {
            caricaPrenotazioni();
        }
    })
    .catch(error => console.error('Errore:', error));
}

/* Sezione inviti */

/* Lista globale degli utenti invitati (e da invitare) a una prenotazione */
let listaInviti = [];
let listaDaInvitare = [];
let idPrenotazione;

/* Ottiene la lista degli utenti gia' invitati a una prenotazione */
function caricaInviti(IDPrenotazione) {  
    document.getElementById('id-prenotazione-invito').value = IDPrenotazione; 
    const formData = new FormData();
    formData.append('azione', 'getInviti');
    formData.append('id', IDPrenotazione);

    fetch("/PlayRoomPlanner/backend/api-gestionePrenotazioni.php", {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            svuotaListaInvitati();
            listaInviti = data.inviti;
            idPrenotazione = data.idPren;
            mostraListaInviti();
        }
    })
    .catch(error => console.error('Errore:', error));
}

/* Controlla la presenza di un utente nel sistema */
function controllaUtente(inputId) {
    const email = document.getElementById(inputId).value;
    
    if (!email) {
        alert('Inserisci un\'email');
        return;
    }

    listaInviti.forEach((invito) => {
        if (email == invito.IscrittoEmail) {
            alert("Errore: utente già invitato");
            return;
        }
    })
    
    const formData = new FormData()
    formData.append('email', email);
    formData.append('azione', 'checkValidEmail');

    fetch('/PlayRoomPlanner/backend/api-gestionePrenotazioni.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            alert(data.message);
        } else {
            if (!listaDaInvitare.includes(email)) {
                listaDaInvitare.push(email);
                mostraListaInviti();
                document.getElementById(inputId).value = "";     
            } else {
                alert("Utente già inserito");
            }
        }
    })
    .catch(error => console.error('Errore: ', error));
}

/* Visualizza la lista degli utenti invitati (e da invitare)
in un oggetto <small> della form inviti */
function mostraListaInviti() {
    const containerInviti = document.getElementById('lista-invitati');
    const titoloInviti = document.getElementById('titolo-prenotazione-inviti');
    const containerDaInvitare = document.getElementById('lista-da-invitare');
    const blockDaInvitare = document.getElementById('block-lista-da-invitare');

    titoloInviti.innerHTML = `Prenotazione ${idPrenotazione}`;
    
    if (listaInviti.length == 0) {
        containerInviti.innerHTML = "<p>Nessun utente invitato</p>";
    } else {
        let html = ''
        listaInviti.forEach((invito) => {
            html += `<p>${invito.IscrittoEmail} - `
            if (invito.Accettazione === 1) {
                html += `Accettato in data ${invito.DataRisposta}`
            } else if (invito.Accettazione === 0) {
                html += `Rifiutato in data ${invito.DataRisposta} con motivazione: "${invito.Motivazione}"`
            } else {
                html += `In attesa di risposta`
            }
            html += `</p>`
        });
        containerInviti.innerHTML = html;
    }

    if(listaDaInvitare.length == 0) {
        blockDaInvitare.style.display = 'none';
    } else {
        blockDaInvitare.style.display = 'block';
        html = '';
        listaDaInvitare.forEach((invitato) => {
            html += `<p>${invitato}</p>`;
        })
        containerDaInvitare.innerHTML = html;
    }

    return;
}

function svuotaListaInvitati() {
    listaInviti.length = 0;
    mostraListaInviti();
}

function svuotaListaDaInvitare() {
    listaDaInvitare.length = 0;
    mostraListaInviti();
}

/* Invita gli utenti */
function invitaUtenti() {
    const formData = new FormData();
    formData.append('IDPren', idPrenotazione);
    formData.append('inviti', JSON.stringify(listaDaInvitare));
    formData.append('azione', 'invita');

    fetch('/PlayRoomPlanner/backend/api-gestionePrenotazioni.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            alert(data.message);
            svuotaListaDaInvitare();
            mostraListaInviti();
        }
        else {
            alert(data.message)
        }
    })
    .catch(error => console.error('Errore: ', error));
}

/* Azioni eseguite solo a contenuti caricati */
document.addEventListener('DOMContentLoaded', function() {
    caricaAule();
    caricaPrenotazioni();
    mostraListaInviti();
    
    /* Attende submit del pulsante crea prenotazione */
    const formCrea = document.getElementById('crea');
    if (formCrea) {
        formCrea.addEventListener('submit', function(e) {
            e.preventDefault();
            inviaForm(this, 'crea');
        });
    }

    /* Attende submit del pulsante modifica prenotazione */
    const formModifica = document.getElementById('modifica');
    if (formModifica) {
        formModifica.addEventListener('submit', function(e) {
            e.preventDefault();
            inviaForm(this, 'modifica');
        });
    }

    /* Consente di aggiungere alla lista invitati premendo Invio */
    document.getElementById("invito-email").addEventListener("keydown", function(event) {
        if (event.key === "Enter") {
            event.preventDefault();
            controllaUtente('invito-email');
            alert('Utente aggiunto alla lista inviti');
        }
    });
});