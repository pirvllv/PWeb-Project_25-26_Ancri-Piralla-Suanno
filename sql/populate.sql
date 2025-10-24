INSERT INTO Iscritto (Email, Cognome, Nome, DataNascita, Foto, Ruolo) VALUES
('mario.rossi@email.com', 'Rossi', 'Mario', '1980-05-15', 'path/to/foto_mr.jpg', 'docente'),
('anna.verdi@email.com', 'Verdi', 'Anna', '1985-09-20', 'path/to/foto_av.jpg', 'docente'),
('luca.bianchi@email.com', 'Bianchi', 'Luca', '2002-11-30', 'path/to/foto_lb.jpg', 'studente'),
('sara.neri@email.com', 'Neri', 'Sara', '2003-03-10', 'path/to/foto_sn.jpg', 'studente'),
('paolo.gialli@email.com', 'Gialli', 'Paolo', '2001-07-25', 'path/to/foto_pg.jpg', 'studente'),
('elisa.bruni@email.com', 'Bruni', 'Elisa', '1990-01-18', 'path/to/foto_eb.jpg', 'tecnico'),
('giulia.russo@email.com', 'Russo', 'Giulia', '2004-02-14', 'path/to/foto_gr.jpg', 'studente'),
('davide.romano@email.com', 'Romano', 'Davide', '1975-12-05', 'path/to/foto_dr.jpg', 'docente');

INSERT INTO Responsabile (ResponsabileEmail, InizioIncarico) VALUES
('mario.rossi@email.com', '2015-09-01'),
('anna.verdi@email.com', '2018-03-10'),
('davide.romano@email.com', '2012-11-01');

INSERT INTO Settore (Nome, Tipologia, ResponsabileEmail) VALUES
('Musica Classica', 'musica', 'mario.rossi@email.com'),
('Danza Moderna', 'danza', 'anna.verdi@email.com'),
('Recitazione', 'teatro', 'davide.romano@email.com'),
('Musica Rock', 'musica', NULL),
('Teatro Sperimentale', 'teatro', NULL);

INSERT INTO Iscrizione (IscrittoEmail, SettoreNome) VALUES
('mario.rossi@email.com', 'Musica Classica'),
('anna.verdi@email.com', 'Danza Moderna'),
('davide.romano@email.com', 'Recitazione'),
('luca.bianchi@email.com', 'Musica Classica'),
('luca.bianchi@email.com', 'Recitazione'),
('sara.neri@email.com', 'Danza Moderna'),
('paolo.gialli@email.com', 'Musica Rock'),
('elisa.bruni@email.com', 'Musica Classica'),
('giulia.russo@email.com', 'Danza Moderna');

INSERT INTO SalaProve (NumAula, Capienza, SettoreNome) VALUES
('M01', 10, 'Musica Classica'),
('M02', 5, 'Musica Classica'),
('D01', 15, 'Danza Moderna'),
('D02', 20, 'Danza Moderna'),
('T01', 25, 'Recitazione'),
('R01', 8, 'Musica Rock');

INSERT INTO Strumentazione (ID, NumAula, Tipologia, Descrizione) VALUES
(1, 'M01', 'strumenti musicali', 'Pianoforte a coda Steinway & Sons'),
(2, 'M01', 'impianti audio', 'Impianto di registrazione stereo'),
(3, 'D01', 'specchi', 'Parete a specchio 10x3m'),
(4, 'T01', 'palcoscenico', 'Palco modulare 5x4m'),
(5, 'R01', 'strumenti musicali', 'Batteria acustica completa'),
(6, 'M02', 'impianti audio', 'Mixer audio 8 canali'),
(7, 'D02', 'specchi', 'Parete a specchio 12x3m con sbarre');

INSERT INTO Prenotazione (IDPrenotazione, DataPren, OraInizio, OraFine, Attivita, NumAula, ResponsabileEmail) VALUES
(1, '2025-11-10', '15:00:00', '17:00:00', 'Prove orchestra', 'M01', 'mario.rossi@email.com'),
(2, '2025-11-10', '17:00:00', '19:00:00', 'Coreografia di gruppo', 'D01', 'anna.verdi@email.com'),
(3, '2025-11-11', '10:00:00', '13:00:00', 'Lettura copione', 'T01', 'davide.romano@email.com'),
(4, '2025-11-12', '11:00:00', '12:00:00', 'Lezione di piano individuale', 'M02', 'mario.rossi@email.com'),
(5, '2025-11-12', '16:00:00', '18:00:00', 'Prove band', 'R01', 'mario.rossi@email.com'),
(6, '2025-11-13', '14:00:00', '16:00:00', 'Riscaldamento e stretching', 'D02', 'anna.verdi@email.com'),
(7, '2025-11-13', '18:00:00', '20:00:00', 'Sessione di improvvisazione', 'T01', 'davide.romano@email.com');

INSERT INTO Invito (IDPrenotazione, IscrittoEmail, Accettazione, Motivazione, DataRisposta) VALUES
(1, 'mario.rossi@email.com', TRUE, NULL, '2025-11-01 10:00:00'),
(1, 'luca.bianchi@email.com', TRUE, NULL, '2025-11-02 11:30:00'),
(1, 'elisa.bruni@email.com', FALSE, 'Conflitto orario con un altro impegno.', '2025-11-03 09:00:00'),
(2, 'anna.verdi@email.com', TRUE, NULL, '2025-11-01 10:05:00'),
(2, 'sara.neri@email.com', TRUE, NULL, '2025-11-04 15:20:00'),
(2, 'giulia.russo@email.com', NULL, NULL, NULL),
(3, 'davide.romano@email.com', TRUE, NULL, '2025-11-02 14:00:00'),
(3, 'luca.bianchi@email.com', TRUE, NULL, '2025-11-03 18:00:00'),
(3, 'elisa.bruni@email.com', TRUE, NULL, '2025-11-05 12:00:00'),
(4, 'mario.rossi@email.com', TRUE, NULL, '2025-11-03 11:00:00'),
(4, 'luca.bianchi@email.com', FALSE, 'Indisponibile', '2025-11-04 10:00:00'),
(5, 'mario.rossi@email.com', TRUE, NULL, '2025-11-04 09:00:00'),
(5, 'paolo.gialli@email.com', TRUE, NULL, '2025-11-05 17:45:00'),
(6, 'anna.verdi@email.com', TRUE, NULL, '2025-11-05 13:00:00'),
(6, 'sara.neri@email.com', NULL, NULL, NULL),
(6, 'giulia.russo@email.com', TRUE, NULL, '2025-11-06 16:00:00'),
(7, 'davide.romano@email.com', TRUE, NULL, '2025-11-06 10:20:00'),
(7, 'luca.bianchi@email.com', NULL, NULL, NULL);
