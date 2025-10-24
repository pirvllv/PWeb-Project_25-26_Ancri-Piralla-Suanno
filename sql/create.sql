CREATE TABLE Iscritto (
    Email VARCHAR(255) NOT NULL PRIMARY KEY,
    Cognome VARCHAR(100) NOT NULL,
    Nome VARCHAR(100) NOT NULL,
    DataNascita DATE NOT NULL,
    Foto VARCHAR(500), -- URL o path
    Ruolo VARCHAR(30) NOT NULL,

    CONSTRAINT chk_Ruolo CHECK (Ruolo IN ('studente', 'docente', 'tecnico'))
);

CREATE TABLE Responsabile (
    ResponsabileEmail VARCHAR(255) NOT NULL, -- vincolo solo docente può essere responsabile espresso in backend
    InizioIncarico DATE NOT NULL,

    FOREIGN KEY (ResponsabileEmail) REFERENCES Iscritto(Email)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE Settore (
    Nome VARCHAR(50) NOT NULL PRIMARY KEY,
    Tipologia VARCHAR(50) NOT NULL,
    ResponsabileEmail VARCHAR(255),

    CONSTRAINT chk_TipologiaSettore CHECK (Tipologia IN ('danza', 'musica', 'teatro')),
    FOREIGN KEY (ResponsabileEmail) REFERENCES Responsabile(ResponsabileEmail)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

CREATE TABLE Iscrizione (
    IscrittoEmail VARCHAR(255) NOT NULL,
    SettoreNome VARCHAR(50) NOT NULL,

    PRIMARY KEY (IscrittoEmail, SettoreNome),
    FOREIGN KEY (IscrittoEmail) REFERENCES Iscritto(Email)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (SettoreNome) REFERENCES Settore(Nome)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE SalaProve (
    NumAula VARCHAR(50) NOT NULL PRIMARY KEY,
    Capienza INT NOT NULL CHECK (Capienza > 0),
    SettoreNome VARCHAR(50),

    FOREIGN KEY (SettoreNome) REFERENCES Settore(Nome)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

CREATE TABLE Strumentazione (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    NumAula VARCHAR(15),
    Tipologia VARCHAR(50) NOT NULL,
    Descrizione VARCHAR(300),

    CONSTRAINT chk_TipologiaStrumentazione CHECK (Tipologia IN ('strumenti musicali', 'impianti audio', 'specchi', 'palcoscenico')),

    FOREIGN KEY (NumAula) REFERENCES SalaProve(NumAula)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

CREATE TABLE Prenotazione (     -- il vincolo di non sovrapposizione verrà imposto in backend
    ID INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    DataPren DATE NOT NULL,
    OraInizio TIME NOT NULL,
    OraFine TIME NOT NULL,
    Attivita VARCHAR(50) NOT NULL,
    NumAula VARCHAR(50) NOT NULL,
    ResponsabileEmail VARCHAR(255) NOT NULL,

    CONSTRAINT ChckDirTemp CHECK (OraFine > OraInizio),

    FOREIGN KEY (ResponsabileEmail) REFERENCES Responsabile(ResponsabileEmail)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    FOREIGN KEY (NumAula) REFERENCES SalaProve(NumAula)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);

CREATE TABLE Invito (
    PrenotazioneID INT NOT NULL,
    IscrittoEmail VARCHAR(255) NOT NULL,
    Accettazione BOOLEAN DEFAULT NULL,
    Motivazione VARCHAR(300) DEFAULT NULL, -- da imporre in backend come NOT NULL quando viene data risposta negativa
    DataRisposta DATETIME DEFAULT NULL,
    
    PRIMARY KEY(PrenotazioneID, IscrittoEmail),

    FOREIGN KEY (PrenotazioneID) REFERENCES Prenotazione(ID)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    FOREIGN KEY (IscrittoEmail) REFERENCES Iscritto(Email)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);
