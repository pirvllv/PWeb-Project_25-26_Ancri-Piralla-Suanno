------------------------------------------------------- 1
-- Contare il numero di partecipanti per ogni prenotazione e verificare che non venga superato il numero di posti per la sala
SELECT p.ID AS ID_Prenotazione, sp.Capienza, COUNT(i.IscrittoEmail) AS NumeroPartecipanti,
CASE WHEN COUNT(i.IscrittoEmail) > sp.Capienza THEN TRUE ELSE FALSE END AS SuperaCapienza
FROM Prenotazione p JOIN Invito i ON p.ID = i.PrenotazioneID JOIN Salaprove sp ON p.NumAula = sp.NumAula
WHERE i.Accettazione = TRUE
GROUP BY p.ID, sp.Capienza
ORDER BY p.ID;
------------------------------------------------------- 1 end


------------------------------------------------------- 2
-- Contare il numero di prenotazioni che sono state organizzate o a cui si è stati invitati, per giorno e per sala di prova.
SELECT Data, NumAula, COUNT(ID) AS NumPrenotCoinvolto
FROM Prenotazione AS p
WHERE p.ID IN (
    SELECT p.ID
    FROM Prenotazione p JOIN Invito i ON p.ID = i.PrenotazioneID
    WHERE i.IscrittoEmail = ? -- passaggio per parametro di un'email di un iscritto (PRIMARY KEY)
)
GROUP BY Data, NumAula
ORDER BY Data, NumAula;
------------------------------------------------------- 2 end


------------------------------------------------------- 3
-- Quando viene definita una prenotazione, verificare che la sala non sia già occupata

------------------------------------------------------- 3 end


------------------------------------------------------- 4
-- Quando un utente accetta un invito, non devono esserci sovrapposizioni con altre prove affinché l’operazione vada a buon fine

------------------------------------------------------- 4 end


------------------------------------------------------- 5
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
------------------------------------------------------- 5 end
