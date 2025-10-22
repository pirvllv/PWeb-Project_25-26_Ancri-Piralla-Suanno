 INSERT INTO Iscritto (Email, Cognome, Nome, DataNascita, Ruolo) VALUES
('mario.rossi@example.com', 'Rossi', 'Mario', '1999-05-15', 'studente'),
('anna.verdi@example.com', 'Verdi', 'Anna', '1975-11-20', 'docente'),
('luca.bianchi@example.com', 'Bianchi', 'Luca', '1988-02-01', 'tecnico'),
('giulia.russo@example.com', 'Russo', 'Giulia', '2001-09-30', 'studente'),
('paolo.neri@example.com', 'Neri', 'Paolo', '1980-07-12', 'docente'),
('antonio.esposito@example.com', 'Esposito', 'Antonio', '1998-01-01', 'studente'),
('sara.galli@example.com', 'Galli', 'Sara', '1995-03-03', 'tecnico'),
('marco.bianco@example.com', 'Bianco', 'Mario', '1992-12-10', 'studente'),
('elena.costa@example.com', 'Costa', 'Elena', '1962-12-10', 'docente'),
('franco.ferri@example.com', 'Ferri', 'Franco', '1985-06-06', 'tecnico'),
('matteo.gatti@example.com', 'Gatti', 'Matteo', '1993-07-07', 'tecnico'),
('chiara.longo@example.com', 'Longo', 'Chiara', '1967-01-19', 'docente'),
('roberto.marini@example.com', 'Marini', 'Roberto', '1969-12-03', 'docente'),
('elisa.gregori@example.com', 'Gregori', 'Elisa', '1991-05-09', 'tecnico'),
('alessio.vitale@example.com', 'Vitale', 'Alessio', '1997-08-11', 'studente');

 INSERT INTO Responsabile (ResponsabileEmail, InizioIncarico) VALUES
('paolo.neri@example.com', '2002-03-12'),
('elena.costa@example.com', '2012-01-17'),
('anna.verdi@example.com', '2022-12-02'),
('elena.costa@example.com', '2000-07-22'),
('roberto.marini@example.com', '1999-04-12'),
('chiara.longo@example.com', '2002-07-13');

 INSERT INTO Settore (Nome, Tipologia, ResponsabileEmail) VALUES
('Musica classica', 'musica', 'paolo.neri@example.com'),
('Musica moderna', 'musica', 'elena.costa@example.com'),
('Danza classica', 'danza', 'anna.verdi@example.com'),
('Danza moderna', 'danza', 'elena.costa@example.com'),
('Teatro impressionista', 'teatro', 'roberto.marini@example.com'),
('Teatro per bambini', 'teatro', 'chiara.longo@example.com');

 INSERT INTO Iscrizione (IscrittoEmail, SettoreNome) VALUES
('mario.rossi@example.com', 'Musica moderna'),
('anna.verdi@example.com', 'Danza classica'),
('luca.bianchi@example.com', 'Musica moderna'),
('giulia.russo@example.com', 'Musica classica'),
('paolo.neri@example.com', 'Musica classica'),
('antonio.esposito@example.com', 'Danza moderna'),
('sara.galli@example.com', 'Teatro impressionista'),
('marco.bianco@example.com', 'Musica moderna'),
('elena.costa@example.com', 'Musica moderna'),
('franco.ferri@example.com', 'Musica classica'),
('matteo.gatti@example.com', 'Teatro per bambini'),
('chiara.longo@example.com', 'Teatro per bambini'),
('roberto.marini@example.com', 'Teatro impressionista'),
('elisa.gregori@example.com', 'Teatro impressionista'),
('alessio.vitale@example.com', 'Danza classica');

 INSERT INTO SalaProve (NumAula, Capienza, SettoreNome) VALUES
('MC01', 30,'Musica classica'),
('MC02', 25,'Musica classica'),
('MM01', 35, 'Musica moderna'),
('DC01', 36, 'Danza classica'),
('DM01', 50, 'Danza moderna'),
('TI01', 50, 'Teatro impressionista'),
('TB01', 20, 'Teatro per bambini'),
('TB02', 25, 'Teatro per bambini');

 INSERT INTO Strumentazione (NumAula, Tipologia, Descrizione) VALUES
('MC01', 'strumenti musicali', 'Viola'),
('MC01', 'strumenti musicali', 'Pianoforte'),
('MC01', 'impianti audio', 'Speaker'),
('MC02', 'strumenti musicali', 'Pianoforte'),
('MC02', 'impianti audio', 'Violoncello'),
('MM01', 'strumenti musicali', 'Pianoforte'),
('DC01', 'specchi', NULL),
('DC01', 'palcoscenico', NULL),
('DM01', 'specchi', NULL),
('DM01', 'palcoscenico', NULL),
('TI01', 'palcoscenico', 'Palco piccolo'),
('TB01', 'palcoscenico', 'Palco grande');

INSERT INTO Prenotazione(OraInizio, OraFine, NumAula, ResponsabileEmail, Attivita) VALUES
('2025-10-22 15:00:00', '2025-10-22 16:00:00', 'MC01', 'paolo.neri@example.com', 'Prove quartetto'),
('2025-10-22 16:00:00', '2025-10-22 17:00:00', 'MC02', 'paolo.neri@example.com', 'Prove orchestra'),
('2025-10-22 12:00:00', '2025-10-22 13:00:00', 'TB01', 'chiara.longo@example.com', 'Lezione'),
('2025-10-22 15:00:00', '2025-10-22 16:00:00', 'DM01', 'elena.costa@example.com', NULL),
('2025-10-22 11:00:00', '2025-10-22 14:00:00', 'TI01', 'roberto.marini@example.com', 'Lezione'),
('2025-10-23 13:00:00', '2025-10-23 15:00:00', 'DC01', 'anna.verdi@example.com', NULL),
('2025-10-23 17:00:00', '2025-10-23 18:00:00', 'DC01', 'anna.verdi@example.com', 'Lezione');

INSERT INTO Invito (PrenotazioneID, IscrittoEmail, Accettazione, DataRisposta, Motivazione) VALUES
(1, 'giulia.russo@example.com', true, '2022-10-10 12:22:30', NULL),
(1, 'matteo.gatti@example.com', NULL, NULL, NULL),
(2, 'mario.rossi@example.com', true, '2022-10-12 12:22:30', NULL),
(3, 'marco.bianco@example.com', false, '2022-10-10 12:22:30', 'Malattia'),
(7, 'luca.bianchi@example.com', true, '2022-10-10 12:32:30', NULL),
(3, 'alessio.vitale@example.com', NULL, NULL, NULL),
(2, 'antonio.esposito@example.com', false, '2022-10-10 12:37:30', 'Famiglia');