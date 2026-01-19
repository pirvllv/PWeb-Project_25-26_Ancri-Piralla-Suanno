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