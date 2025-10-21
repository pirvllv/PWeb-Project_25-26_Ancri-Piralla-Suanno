 INSERT INTO Iscritto (Email, Cognome, Nome, DataNascita, Foto, Ruolo) VALUES
('mario.rossi@example.com', 'Rossi', 'Mario', '1999-05-15', NULL, 'studente'),
('anna.verdi@example.com', 'Verdi', 'Anna', '1975-11-20', NULL, 'docente'),
('luca.bianchi@example.com', 'Bianchi', 'Luca', '1988-02-01', NULL, 'tecnico'),
('giulia.russo@example.com', 'Russo', 'Giulia', '2001-09-30', NULL, 'studente'),
('paolo.neri@example.com', 'Neri', 'Paolo', '1980-07-12', NULL, 'docente'),
('antonio.esposito@example.com', 'Esposito', 'Antonio', '1998-01-01', NULL, 'studente'),
('sara.galli@example.com', 'Galli', 'Sara', '1995-03-03', NULL, 'tecnico'),
('marco.bianco@example.com', 'Bianco', 'Mario', '1992-12-10', NULL, 'studente'),
('elena.costa@example.com', 'Costa', 'Elena', '1962-12-10', NULL, 'docente'),
('franco.ferri@example.com', 'Ferri', 'Franco', '1985-06-06', NULL, 'tecnico');

 INSERT INTO Settore (Nome, Tipologia, NumIscritti, ResponsabileEmail) VALUES
('Musica classica', 'musica', 5,'paolo.neri@example.com'),
('Musica moderna', 'musica', 6, 'elena.costa@example.com'),
('Danza classica', 'danza', 10, 'anna.verdi@example.com'),
('Danza moderna', 'danza', 11, 'elena.costa@example.com'),
('Teatro impressionista', 15, '1980-07-12', NULL, 'docente'),
('Teatro per bambini', 'teatro', 4, NULL, 'studente');
