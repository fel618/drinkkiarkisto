-- Käyttäjät
CREATE TABLE Kayttaja (
    KayttajaID INT(11) AUTO_INCREMENT PRIMARY KEY,
    Kayttajatunnus VARCHAR(50) NOT NULL UNIQUE,
    Salasana VARCHAR(255) NOT NULL,
    Sahkoposti VARCHAR(100) NOT NULL,
    Rooli VARCHAR(20) NOT NULL
) ENGINE=InnoDB;

-- Drinkit
CREATE TABLE Drinkki (
    DrinkkiID INT(11) AUTO_INCREMENT PRIMARY KEY,
    Nimi VARCHAR(100) NOT NULL,
    Juomalaji VARCHAR(50) NOT NULL,
    Valmistusohje TEXT NOT NULL,
    Hyvaksytty TINYINT(1) NOT NULL DEFAULT 0,
    Lisaaja INT(11),
    FOREIGN KEY (Lisaaja) REFERENCES Kayttaja(KayttajaID)
        ON DELETE SET NULL
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Ainekset
CREATE TABLE Aines (
    AinesID INT(11) AUTO_INCREMENT PRIMARY KEY,
    Nimi VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- Liitostaulu: drinkki – aines
CREATE TABLE Drinkki_Aines (
    DrinkkiID INT(11),
    AinesID INT(11),
    Maara VARCHAR(30) NOT NULL,
    PRIMARY KEY (DrinkkiID, AinesID),
    FOREIGN KEY (DrinkkiID) REFERENCES Drinkki(DrinkkiID)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (AinesID) REFERENCES Aines(AinesID)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;
