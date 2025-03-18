drop database if exists RH_CPI;
create database if not exists RH_CPI;
use RH_CPI;

-- Création de la table situation_administratif
CREATE TABLE situation_administratif (
    idFonctionaire INT(4) AUTO_INCREMENT PRIMARY KEY,
    NOM VARCHAR(50),
    PRENOM VARCHAR(50),
    PPR INT(10) UNIQUE,
    CNIE VARCHAR(50),
    GRADE VARCHAR(50),
    FONCTION VARCHAR(250),
    SERVICE CHAR(50)
);

-- Création de la table avencement_grade
CREATE TABLE avencement_grade (
    idFonctionaire INT(4) AUTO_INCREMENT PRIMARY KEY,
    NOM VARCHAR(50),
    PRENOM VARCHAR(50),
    PPR INT(10) UNIQUE,
    CNIE VARCHAR(50),
    CADRE VARCHAR(50),
    GRADE VARCHAR(50),
    ECHELLE VARCHAR(250),
    ECHELON VARCHAR(50),
    INDICE VARCHAR(50)
);

-- Création de la table maladie
CREATE TABLE maladie (
    idFonctionaire INT(4) AUTO_INCREMENT PRIMARY KEY,
    NOM VARCHAR(50),
    PRENOM VARCHAR(50),
    PPR INT(10) UNIQUE,
    CNIE VARCHAR(50),
    TYPES_DE_CERTIFICAT VARCHAR(100),
    NOMBRE_DES_JOURS INT(10)
);

-- Création de la table congé_et_autorisation
CREATE TABLE conge_et_autorisation (
    idFonctionaire INT(4) AUTO_INCREMENT PRIMARY KEY,
    NOM VARCHAR(50),
    PRENOM VARCHAR(50),
    PPR INT(10) UNIQUE,
    CNIE VARCHAR(50),
    NOMBRE_DES_JOURS INT(10),
    DATE_DE_DEPART DATE,
    RELIQUAT_EN_JOUR INT(10)
);

-- Création de la table position_fonctionnaire (car "Position" est un mot réservé)
CREATE TABLE position_fonctionnaire (
    idFonctionaire INT(4) AUTO_INCREMENT PRIMARY KEY,
    NOM VARCHAR(50),
    PRENOM VARCHAR(50),
    PPR INT(10) UNIQUE,
    CNIE VARCHAR(50),
    Type_de_decision VARCHAR(50),
    Direction_receptrice VARCHAR(50),
    date_de_decision DATE
);

-- Création de la table Mouvement
CREATE TABLE Mouvement (
    idFonctionaire INT(4) AUTO_INCREMENT PRIMARY KEY,
    NOM VARCHAR(50),
    PRENOM VARCHAR(50),
    PPR INT(10) UNIQUE,
    CNIE VARCHAR(50),
    Motif VARCHAR(200),
    Date_de_decision DATE
);
create table fonct(
-- Ajout de la clé étrangère reliant situation_administratif et avencement_grade sur PPR
ALTER TABLE situation_administratif 
ADD CONSTRAINT fk_situation_avencement 
FOREIGN KEY (PPR) REFERENCES avencement_grade(PPR);
-- Création de la table situation_personnelle
use RH_CPI;
CREATE TABLE situation_personnelle (
    idFonctionnaire INT(4) AUTO_INCREMENT PRIMARY KEY,
    NOM VARCHAR(50) NOT NULL,
    PRENOM VARCHAR(50) NOT NULL,
    PPR INT(10) UNIQUE NOT NULL,
    CIN VARCHAR(15) UNIQUE NOT NULL,
    DATE_NAISSANCE DATE,
    LIEU_NAISSANCE VARCHAR(100),
    ADRESSE VARCHAR(255),
    SITUATION_FAMILIALE ENUM('Célibataire', 'Marié(e)', 'Divorcé(e)', 'Veuf(ve)') DEFAULT 'Célibataire',
    NBR_ENFANT INT(2) DEFAULT 0,
    TELEPHONE VARCHAR(20),
    SEXE ENUM('Homme', 'Femme') DEFAULT 'Homme'
);

-- Ajout de contraintes avec les autres tables
ALTER TABLE situation_personnelle 
ADD CONSTRAINT fk_situation_personnelle_admin 
FOREIGN KEY (PPR) REFERENCES situation_administratif(PPR) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE situation_administratif
ADD COLUMN DATE_RECRUTEMENT DATE,
ADD COLUMN DATE_FONCTION DATE,
ADD COLUMN DATE_AFFECTATION DATE,
ADD COLUMN ANCIENNETE_GRADE VARCHAR(50),
ADD COLUMN ANCIENNETE_ECHELON VARCHAR(50);