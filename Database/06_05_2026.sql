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
<<<<<<< Updated upstream
    FOREIGN KEY (users_id) REFERENCES Users(id)
=======
    FOREIGN KEY (users_id) REFERENCES Users(id) ON DELETE CASCADE
>>>>>>> Stashed changes
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
<<<<<<< Updated upstream
    FOREIGN KEY (users_id) REFERENCES Users(id),
    FOREIGN KEY (objectifs_id) REFERENCES objectifs(id)
=======
    FOREIGN KEY (users_id) REFERENCES Users(id)ON DELETE CASCADE, 
    FOREIGN KEY (objectifs_id) REFERENCES objectifs(id) ON DELETE CASCADE
>>>>>>> Stashed changes
);
CREATE TABLE regimes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    objective_id INT,
<<<<<<< Updated upstream
    variation_poids DECIMAL(5,2),
    duree_jours INT,
    prix DECIMAL(10,2),

    pourcentage_viande DECIMAL(5,2),
    pourcentage_poisson DECIMAL(5,2),
    pourcentage_volaille DECIMAL(5,2),

    FOREIGN KEY (objective_id) REFERENCES objectives(id)
);
=======
    variation_poids DECIMAL(10,2),
    FOREIGN KEY (objective_id) REFERENCES objectives(id) ON DELETE CASCADE
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
    FOREIGN KEY (users_id) REFERENCES Users(id) ON DELETE CASCADE,
    FOREIGN KEY (regime_id) REFERENCES regimes(id) ON DELETE CASCADE
);
CREATE TABLE activite_sportive(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    objective_id INT,
    duree_minutes INT,
    FOREIGN KEY (objective_id) REFERENCES objectifs(id) ON DELETE CASCADE
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
>>>>>>> Stashed changes
