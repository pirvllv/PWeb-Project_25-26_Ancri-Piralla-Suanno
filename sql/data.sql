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
('franco.ferri@example.com', 'Ferri', 'Franco', '1985-06-06', 'tecnico')
('matteo.gatti@example.com', 'Gatti', 'Matteo', '1993-07-07', 'tecnico'),
('chiara.longo@example.com', 'Longo', 'Chiara', '1967-01-19', 'docente'),
('roberto.marini@example.com', 'Marini', 'Roberto', '1969-12-03', 'docente'),
('elisa.gregori@example.com', 'Gregori', 'Elisa', '1991-05-09', 'tecnico'),
('alessio.vitale@example.com', 'Vitale', 'Alessio', '1997-08-11', 'studente');

 INSERT INTO Settore (Nome, Tipologia, NumIscritti, ResponsabileEmail) VALUES
('Musica classica', 'musica', 3,'paolo.neri@example.com'),
('Musica moderna', 'musica', 4, 'elena.costa@example.com'),
('Danza classica', 'danza', 2, 'anna.verdi@example.com'),
('Danza moderna', 'danza', 1, 'elena.costa@example.com'),
('Teatro impressionista', 'teatro', 2, 'roberto.marini@example.com'),
('Teatro per bambini', 'teatro', 2, 'chiara.longo@example.com');

 INSERT INTO Responsabile (ResponsabileEmail, InizioIncarico, AnniServizio) VALUES
('paolo.neri@example.com', '2002-03-12', 23),
('elena.costa@example.com', '2012-01-17', 13),
('anna.verdi@example.com', '2022-12-02', 3),
('elena.costa@example.com', '2000-07-22', 25),
('roberto.marini@example.com', '1999-04-12', 26),
('chiara.longo@example.com', '2002-07-13', 23);

 INSERT INTO Iscrizione (EmailIscritto, SettoreNome) VALUES
('mario.rossi@example.com', 'Musica moderna'),
('anna.verdi@example.com', 'Danza classica', ),
('luca.bianchi@example.com', 'Musica moderna'),
('giulia.russo@example.com', 'Musica classica'),
('paolo.neri@example.com', 'Musica classica'),
('antonio.esposito@example.com', 'Danza moderna'),
('sara.galli@example.com', 'Teatro impressionista'),
('marco.bianco@example.com', 'Musica moderna'),
('elena.costa@example.com', 'Costa', 'Musica moderna'),
('franco.ferri@example.com', 'Musica classica')
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

 INSERT INTO SalaProve (NumAula, Tipologia, Descrizione) VALUES
('MC01', 'Strumenti musicali'),
('MC01', 'Strumenti musicali'),
('MC01', 'Strumenti musicali'),
('MC02', 'Strumenti musicali'),
('MC02', 'Musica classica'),
('MM01', 'Musica moderna'),
('DC01', 'Danza classica'),
('DM01', 'Danza moderna'),
('TI01', 'Teatro impressionista'),
('TB01', 'Teatro per bambini'),
('TB02', 'Teatro per bambini');