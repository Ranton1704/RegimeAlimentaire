CREATE DATABASE Regime_db;
USE Regime_db;

CREATE TABLE users (
     id INT PRIMARY KEY AUTO_INCREMENT,
     nom VARCHAR(150) NOT NULL,
     prenom VARCHAR(150) NOT NULL,
     email VARCHAR(255) NOT NULL UNIQUE,
     genre ENUM('Homme', 'Femme', 'Autre') NOT NULL,
     mot_de_passe VARCHAR(255) NOT NULL,
     solde DECIMAL(10,2) DEFAULT 0.00,
     is_gold BOOLEAN DEFAULT FALSE 
);
CREATE TABLE profil_sante(
    id INT PRIMARY KEY AUTO_INCREMENT,
    users_id INT NOT NULL,
    poids DECIMAL(10,2) NOT NULL,
    taille DECIMAL(10,2) NOT NULL,
    age INT NOT NULL,
    imc DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (users_id) REFERENCES users(id) ON DELETE CASCADE
);
CREATE TABLE objectifs(
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    description TEXT NOT NULL

);
CREATE TABLE objectifs_users(
    id INT PRIMARY KEY AUTO_INCREMENT,
    users_id INT NOT NULL,
    objectifs_id INT NOT NULL,
    FOREIGN KEY (users_id) REFERENCES users(id)ON DELETE CASCADE, 
    FOREIGN KEY (objectifs_id) REFERENCES objectifs(id) ON DELETE CASCADE
);
CREATE TABLE regimes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    objectifs_id INT,
    variation_poids DECIMAL(10,2),
    FOREIGN KEY (objectifs_id) REFERENCES objectifs(id) ON DELETE CASCADE
);
CREATE TABLE regimes_prix(
    id INT AUTO_INCREMENT PRIMARY KEY,
    regime_id INT NOT NULL,
    prix DECIMAL(10,2) NOT NULL,
    duree_jours INT NOT NULL,
    FOREIGN KEY (regime_id) REFERENCES regimes(id) ON DELETE CASCADE
);
CREATE TABLE regimes_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    users_id INT NOT NULL,
    regime_id INT NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    FOREIGN KEY (users_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (regime_id) REFERENCES regimes(id) ON DELETE CASCADE
);
CREATE TABLE activite_sportive(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    objectifs_id INT,
    duree_minutes INT,
    FOREIGN KEY (objectifs_id) REFERENCES objectifs(id) ON DELETE CASCADE
);
CREATE TABLE activite_sportive_users(
    id INT AUTO_INCREMENT PRIMARY KEY,
    users_id INT NOT NULL,
    activite_sportive_id INT NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    FOREIGN KEY (users_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (activite_sportive_id) REFERENCES activite_sportive(id) ON DELETE CASCADE
);
CREATE TABLE portfeuille_code(
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    utilise_le DATE  NULL,
    used_by INT,
    utilise BOOLEAN DEFAULT FALSE,
    montant DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (used_by) REFERENCES users(id) ON DELETE SET NULL
);
CREATE TABLE historique_achats(
    id INT AUTO_INCREMENT PRIMARY KEY,
    users_id INT NOT NULL,
    regime_id INT NOT NULL,
    date_achat DATE NOT NULL,
    montant DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (users_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (regime_id) REFERENCES regimes(id) ON DELETE CASCADE
);