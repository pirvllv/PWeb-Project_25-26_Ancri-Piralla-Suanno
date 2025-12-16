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

function caricaPrenotazioni() {
    fetch('../backend/api-gestionePrenotazioni.php?azione=mostraPren')
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

function caricaAule() {
    fetch('../backend/api-gestionePrenotazioni.php?azione=getAule')
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

function inviaForm(form, azione) {
    const formData = new FormData(form);
    formData.append('azione', azione);

    fetch('../backend/api-gestionePrenotazioni.php', {
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

function eliminaPrenotazione(id) {
    if (!confirm('Sei sicuro di voler eliminare questa prenotazione?')) return;

    const formData = new FormData();
    formData.append('azione', 'elimina');
    formData.append('IDPrenotazione', id);

    fetch('../backend/api-gestionePrenotazioni.php', {
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

const listaInviti = [];

function caricaInviti(IDPrenotazione) {   
    const formData = new FormData();
    formData.append('azione', 'getInviti');
    formData.append('id', IDPrenotazione);

    fetch("../backend/api-gestionePrenotazioni.php", {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            listaInviti = data.inviti;
        }
    })
    .catch(error => console.error('Errore:', error));
}

function controllaUtente(inputId) {
    const email = document.getElementById(inputId).value;
    
    if (!email) {
        alert('Inserisci un\'email');
        return;
    }
    
    const formData = new FormData()
    formData.append('emailInvitato', email);
    formData.append('azione', 'checkValidEmail');
    fetch('../backend/api-gestionePrenotazioni.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            alert(data.message);
        } else {
            if (!listaInviti.includes(email)) {
                listaInviti.push(email);
                mostraListaInviti();
                document.getElementById(inputId).value = "";
                
            } else {
                alert("Utente già inserito");
            }
        }
    })
    .catch(error => console.error('Errore: ', error));
}

function mostraListaInviti() {
    const container = document.getElementById('lista-invitati');

    if (listaInviti.length == 0) {
        container.innerHTML = "<p>Nessun utente aggiunto</p>";
        return;
    }

    let html = '<p>'

    listaInviti.forEach( (i, index) => {
        if (listaInviti.length -1 == index) {
            html += `${i}</p>`
        } else {
            html += `${i}, `
        }
    });

    container.innerHTML = html;
}

function svuotaLista() {
    listaInviti.length = 0;
    mostraListaInviti();
}

document.addEventListener('DOMContentLoaded', function() {
    caricaAule();
    caricaPrenotazioni();
    mostraListaInviti();
    
    const formCrea = document.getElementById('crea');
    if (formCrea) {
        formCrea.addEventListener('submit', function(e) {
            e.preventDefault();
            inviaForm(this, 'crea');
        });
    }

    const formModifica = document.getElementById('modifica');
    if (formModifica) {
        formModifica.addEventListener('submit', function(e) {
            e.preventDefault();
            inviaForm(this, 'modifica');
        });
    }

    document.getElementById("invito-email").addEventListener("keydown", function(event) {
    if (event.key === "Enter") {
        event.preventDefault();
        controllaUtente('invito-email');
        alert('Utente aggiunto alla lista inviti');
    }
  });
});