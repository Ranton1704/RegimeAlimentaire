CREATE DATABASE Regime_db;
USE Regime_db;

CREATE TABLE Users (
     id INT PRIMARY KEY AUTO_INCREMENT,
     nom VARCHAR(150) NOT NULL,
     prenom VARCHAR(150) NOT NULL,
     email VARCHAR(255) NOT NULL UNIQUE,
     genre ENUM('Homme', 'Femme', 'Autre') NOT NULL,
     mot_de_passe VARCHAR(255) NOT NULL
);
CREATE TABLE profil_sante(
    id INT PRIMARY KEY AUTO_INCREMENT,
    users_id INT NOT NULL,
    poids DECIMAL(10,2) NOT NULL,
    taille DECIMAL(10,2) NOT NULL,
    age INT NOT NULL,
    imc DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (users_id) REFERENCES Users(id)
);
CREATE TABLE objetifs(
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    description TEXT NOT NULL

);
CREATE TABLE objectifs_users(
    id INT PRIMARY KEY AUTO_INCREMENT,
    users_id INT NOT NULL,
    objectifs_id INT NOT NULL,
    FOREIGN KEY (users_id) REFERENCES Users(id),
    FOREIGN KEY (objectifs_id) REFERENCES objectifs(id)
);
