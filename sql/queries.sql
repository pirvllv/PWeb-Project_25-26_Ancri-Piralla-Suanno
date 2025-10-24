------------------------------------------------------- a
-- Contare il numero di partecipanti per ogni prenotazione e verificare che non venga superato il numero di posti per la sala
SELECT p.IDPrenotazione, sp.Capienza, COUNT(i.IscrittoEmail),
CASE WHEN COUNT(i.IscrittoEmail) > sp.Capienza THEN TRUE ELSE FALSE END
FROM Prenotazione p JOIN Invito i ON p.IDPrenotazione = i.IDPrenotazione JOIN SalaProve sp ON p.NumAula = sp.NumAula
WHERE i.Accettazione = TRUE
GROUP BY p.IDPrenotazione, sp.Capienza
ORDER BY p.IDPrenotazione;
------------------------------------------------------- a end




------------------------------------------------------- b-1
-- Contare il numero di prenotazioni che sono state organizzate, per giorno e per sala di prova.
SELECT DataPren, NumAula, COUNT(IDPrenotazione)
FROM Prenotazione AS p
WHERE p.IDPrenotazione IN (
    SELECT p.IDPrenotazione -- elenco ID prenotazioni organizzate da responsabile
    FROM Prenotazione AS p
    WHERE ResponsabileEmail = ?
)
GROUP BY DataPren, NumAula
ORDER BY DataPren, NumAula;
------------------------------------------------------- b-1 end




------------------------------------------------------- b-2
-- Contare il numero di prenotazioni a cui si è stati invitati, per giorno e per sala di prova.
SELECT DataPren, NumAula, COUNT(IDPrenotazione)
FROM Prenotazione AS p
WHERE p.IDPrenotazione IN (
    SELECT i.IDPrenotazione -- elenco ID prenotazioni a cui un utente è stato invitato
    FROM Invito AS i
    WHERE IscrittoEmail = ?
)
GROUP BY DataPren, NumAula
ORDER BY DataPren, NumAula;
------------------------------------------------------- b-2 end




------------------------------------------------------- c
-- Quando viene definita una prenotazione, verificare che la sala non sia già occupata
SELECT EXISTS(
    SELECT IDPrenotazione
    FROM Prenotazione
    WHERE NumAula = ? AND DataPren = ? AND OraInizio < ? AND OraFine > ?
);  -- OraInizio < OraFineNuovaPren AND OraFine > OraInizioNuovaPren
------------------------------------------------------- c end




------------------------------------------------------- d
-- Quando un utente accetta un invito, non devono esserci sovrapposizioni con altre prove affinché l’operazione vada a buon fine
SELECT EXISTS (
    SELECT 1
    FROM Prenotazione AS p
    JOIN Invito AS i ON p.IDPrenotazione = i.IDPrenotazione
    WHERE
        i.IscrittoEmail = ?
        AND i.Accettazione = TRUE
        AND p.IDPrenotazione <> ?
        AND p.DataPren = ?
        AND p.OraInizio < ?
        AND p.OraFine > ?
);      -- OraInizio < OraFineNuovaPren AND OraFine > OraInizioNuovaPren
------------------------------------------------------- d end




------------------------------------------------------- e
-- Trovare le prenotazioni a cui hanno partecipato un numero di membri dell’associazione che è superiore al numero di membri che afferiscono al settore del responsabile che ha organizzato la prova.
WITH CountPartecipanti AS (
    SELECT PrenotazioneID, COUNT(IscrittoEmail) AS NumPartecipanti
    FROM Invito
    WHERE Accettazione = TRUE
    GROUP BY PrenotazioneID
);

SELECT p.ID AS ID_Prenotazione, s.Nome AS NomeSettore, s.NumIscritti AS IscrittiNelSettore, cp.NumeroPartecipanti
FROM Prenotazione p JOIN Settore s ON p.ResponsabileEmail = s.ResponsabileEmail JOIN CountPartecipanti cp ON p.ID = cp.PrenotazioneID
WHERE cp.NumeroPartecipanti > s.NumIscritti
ORDER BY p.ID;
------------------------------------------------------- e end
