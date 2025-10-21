DROP TABLE IF EXISTS Iscritto;
DROP TABLE IF EXISTS Responsabile;
DROP TABLE IF EXISTS Settore;
DROP TABLE IF EXISTS Iscrizione;
DROP TABLE IF EXISTS SalaProve;
DROP TABLE IF EXISTS Strumentazione;
DROP TABLE IF EXISTS Prenotazione;
DROP TABLE IF EXISTS Invito;

CREATE TABLE Iscritto (
    Email VARCHAR(100) NOT NULL PRIMARY KEY,
    Cognome VARCHAR(100) NOT NULL,
    Nome VARCHAR(100) NOT NULL,
    DataNascita DATE NOT NULL,
    Foto VARCHAR(500), -- URL o path
    Ruolo VARCHAR(10) NOT NULL,

    CONSTRAINT chk_Ruolo CHECK (Ruolo IN ('studente', 'docente', 'tecnico'))
);

CREATE TABLE Responsabile (
    ResponsabileEmail VARCHAR(255) NOT NULL, -- vincolo solo docente può essere resp. non esprimibile
    InizioIncarico DATE NOT NULL,
    AnniServizio INT DEFAULT 0,

    FOREIGN KEY (ResponsabileEmail) REFERENCES Iscritto(Email)
        ON DELETE NO ACTION
        ON UPDATE CASCADE
);

CREATE TABLE Settore (
    Nome VARCHAR(100) NOT NULL PRIMARY KEY,
    Tipologia ENUM() NOT NULL,
    NumIscritti INT DEFAULT 0,
    ResponsabileEmail VARCHAR(255) NOT NULL,

    --CONSTRAINT chk_TipologiaSettore CHECK (Tipologia IN ('danza', 'musica', 'teatro')),
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
    ID INT AUTO_INCREMENT PRIMARY KEY, -- su internet dicono sia corretto, controllerò su file di Perlasca
    NumAula VARCHAR(50) NOT NULL,
    Tipologia VARCHAR(100) NOT NULL,
    Descrizione VARCHAR(300), -- 300 numero arbitrario, è anche possibile che la descrizione sia vuota

    CONSTRAINT chk_TipologiaStrumenti CHECK (Tipologia IN ('strumenti musicali', 'impianti audio', 'specchi', 'palcoscenico')),
    FOREIGN KEY (NumAula) REFERENCES SalaProve(NumAula)
        ON DELETE CASCADE -- se elimino un'aula, elimino la sua strumentazione (?)
        ON UPDATE CASCADE
);

CREATE TABLE Prenotazione (
    
    ID INT NOT NULL PRIMARY KEY,
    DataPren DATE NOT NULL,
    OraInizio TIMESTAMP NULL DEFAULT NULL,
    OraFine TIMESTAMP NULL DEFAULT NULL,
    Attivita VARCHAR(50),
    NumAula VARCHAR(50) NOT NULL,
    ResponsabileEmail VARCHAR(100),

    FOREIGN KEY (ResponsabileEmail) REFERENCES Responsabile(ResponsabileEmail)
        ON UPDATE CASCADE
        ON DELETE NO ACTION,
    FOREIGN KEY (NumAula) REFERENCES SalaProve(NumAula)
        ON UPDATE CASCADE
        ON DELETE NO ACTION
);

CREATE TABLE Invito (

    PrenotazioneID INT NOT NULL,
    IscrittoEmail VARCHAR(100) NOT NULL,
    Accettazione BOOLEAN,
    Motivazione VARCHAR(300),
    DataRisposta DATE,
    
    PRIMARY KEY(PrenotazioneID, IscrittoEmail),

    FOREIGN KEY (PrenotazioneID) REFERENCES Prenotazione(ID)
        ON UPDATE CASCADE
        ON DELETE NO ACTION,
    FOREIGN KEY (IscrittoEmail) REFERENCES Iscritto(Email)
        ON UPDATE CASCADE
        ON DELETE CASCADE

);