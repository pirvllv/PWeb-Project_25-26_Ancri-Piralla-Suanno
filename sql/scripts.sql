CREATE TABLE Iscritto (
    Email VARCHAR(100) NOT NULL PRIMARY KEY,
    Cognome VARCHAR(100) NOT NULL,
    Nome VARCHAR(100) NOT NULL,
    DataNascita DATE NOT NULL,
    Foto VARCHAR(500), -- URL o path
    Ruolo VARCHAR(30) NOT NULL,

    CONSTRAINT chk_Ruolo CHECK (Ruolo IN ('studente', 'docente', 'tecnico'))
);

CREATE TABLE Responsabile (
    ResponsabileEmail VARCHAR(255) NOT NULL, -- vincolo solo docente può essere resp. non esprimibile
    InizioIncarico DATE NOT NULL,

    FOREIGN KEY (ResponsabileEmail) REFERENCES Iscritto(Email)
        ON DELETE NO ACTION
        ON UPDATE CASCADE
);

CREATE TABLE Settore (
    Nome VARCHAR(50) NOT NULL PRIMARY KEY,
    Tipologia VARCHAR(50) NOT NULL,
    ResponsabileEmail VARCHAR(255) NOT NULL,

    CONSTRAINT chk_TipologiaSettore CHECK (Tipologia IN ('danza', 'musica', 'teatro')),
    FOREIGN KEY (ResponsabileEmail) REFERENCES Responsabile(ResponsabileEmail)
);

CREATE TABLE Iscrizione (
    IscrittoEmail VARCHAR(255) NOT NULL,
    SettoreNome VARCHAR(100) NOT NULL,

    PRIMARY KEY (IscrittoEmail, SettoreNome),
    FOREIGN KEY (IscrittoEmail) REFERENCES Iscritto(Email)
        ON DELETE NO ACTION
        ON UPDATE CASCADE,
    FOREIGN KEY (SettoreNome) REFERENCES Settore(Nome)
        ON DELETE NO ACTION
        ON UPDATE CASCADE
);

CREATE TABLE SalaProve (
    NumAula VARCHAR(50) NOT NULL PRIMARY KEY,
    Capienza INT NOT NULL CHECK (Capienza > 0),
    SettoreNome VARCHAR(100) NOT NULL,

    FOREIGN KEY (SettoreNome) REFERENCES Settore(Nome)
        ON DELETE NO ACTION
        ON UPDATE CASCADE
);

CREATE TABLE Strumentazione (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    NumAula VARCHAR(15) NOT NULL,
    Tipologia VARCHAR(50) NOT NULL,
    Descrizione VARCHAR(300),

    CONSTRAINT chk_TipologiaStrumentazione CHECK (Tipologia IN ('strumenti musicali', 'impianti audio', 'specchi', 'palcoscenico')),

    FOREIGN KEY (NumAula) REFERENCES SalaProve(NumAula)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

ALTER TABLE Strumentazione AUTO_INCREMENT=1;

CREATE TABLE Prenotazione (
    
    ID INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    DataPren DATE NULL DEFAULT NULL,
    OraInizio TIME NULL DEFAULT NULL, -- ? come mai inizializzi con NULL DEFAULT NULL?
    OraFine TIME NULL DEFAULT NULL,
    Attivita VARCHAR(50),
    NumAula VARCHAR(50) NOT NULL,
    ResponsabileEmail VARCHAR(100),

    CONSTRAINT ChckDirTemp CHECK (OraFine > OraInizio),

    FOREIGN KEY (ResponsabileEmail) REFERENCES Responsabile(ResponsabileEmail)
        ON UPDATE CASCADE
        ON DELETE NO ACTION,
    FOREIGN KEY (NumAula) REFERENCES SalaProve(NumAula)
        ON UPDATE CASCADE
        ON DELETE NO ACTION
);

ALTER TABLE Prenotazione AUTO_INCREMENT=1;

CREATE TABLE Invito (

    PrenotazioneID INT NOT NULL,
    IscrittoEmail VARCHAR(100) NOT NULL,
    Accettazione BOOLEAN,
    Motivazione VARCHAR(300),
    DataRisposta TIMESTAMP,
    
    PRIMARY KEY(PrenotazioneID, IscrittoEmail),

    FOREIGN KEY (PrenotazioneID) REFERENCES Prenotazione(ID)
        ON UPDATE CASCADE
        ON DELETE NO ACTION,
    FOREIGN KEY (IscrittoEmail) REFERENCES Iscritto(Email)
        ON UPDATE CASCADE
        ON DELETE CASCADE

);
