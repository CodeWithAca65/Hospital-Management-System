CREATE DATABASE klinika;

USE klinika;

CREATE TABLE Pacijenti (
    pacijent_id VARCHAR(255) PRIMARY KEY NOT NULL,
    ime VARCHAR(255),
    prezime VARCHAR(255),
    kontakt VARCHAR(255)
)ENGINE=InnoDB;

CREATE TABLE MedicinskeSestre (
    medicinska_sestra_id INT PRIMARY KEY AUTO_INCREMENT,
    ime VARCHAR(255),
    prezime VARCHAR(255),
    kontakt VARCHAR(255)
)ENGINE=InnoDB;

CREATE TABLE Doktori (
    doktor_id INT PRIMARY KEY AUTO_INCREMENT,
    korisnicko_ime VARCHAR(255) NOT NULL,
    ime VARCHAR(255),
    prezime VARCHAR(255),
    specijalnost VARCHAR(255)
)ENGINE=InnoDB;

CREATE TABLE VlasniciKlinike (
    vlasnik_id INT PRIMARY KEY AUTO_INCREMENT,
    ime VARCHAR(255),
    prezime VARCHAR(255),
    kontakt VARCHAR(255)
)ENGINE=InnoDB;

CREATE TABLE Pregledi (
    pregled_id INT PRIMARY KEY AUTO_INCREMENT,
    pacijent_id VARCHAR(255),
    doktor_id INT,
    datum DATE,
    vreme TIME,
    status ENUM('nije_pregledan', 'pregledan') NOT NULL,
    informacije_o_pregledu TEXT,
    FOREIGN KEY (pacijent_id) REFERENCES Pacijenti(pacijent_id),
    FOREIGN KEY (doktor_id) REFERENCES Doktori(doktor_id)
)ENGINE=InnoDB;

CREATE TABLE Rasporedi (
    doktor_id INT,
    smena VARCHAR(50),
    PRIMARY KEY (doktor_id, smena),
    FOREIGN KEY (doktor_id) REFERENCES Doktori(doktor_id)
)ENGINE=InnoDB;

CREATE TABLE nalozi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    uloga ENUM('vlasnik', 'doktor', 'medicinska_sestra') NOT NULL,
    korisnicko_ime VARCHAR(255) NOT NULL,
    sifra VARCHAR(255) NOT NULL
)ENGINE=InnoDB;

-- Dodavanje vlasnika
INSERT INTO nalozi (uloga, korisnicko_ime, sifra) VALUES ('vlasnik', 'vlasnik', 'vlasnik');