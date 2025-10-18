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
    AnniServizio INT,

    FOREIGN KEY (ResponsabileEmail) REFERENCES Iscritto(Email)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE Settore (
    Nome VARCHAR(100) NOT NULL PRIMARY KEY,
    Tipologia VARCHAR(50) NOT NULL,
    NumIscritti INT DEFAULT 0,
    ResponsabileEmail VARCHAR(255) NOT NULL,

    CONSTRAINT chk_TipologiaSettore CHECK (Tipologia IN ('danza', 'musica', 'teatro')),
    FOREIGN KEY (ResponsabileEmail) REFERENCES Responsabile(ResponsabileEmail)
);

CREATE TABLE Iscrizione (
    IscrittoEmail VARCHAR(255) NOT NULL,
    SettoreNome VARCHAR(100) NOT NULL,

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
    SettoreNome VARCHAR(100) NOT NULL,

    FOREIGN KEY (SettoreNome) REFERENCES Settore(Nome)
        ON DELETE CASCADE
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
