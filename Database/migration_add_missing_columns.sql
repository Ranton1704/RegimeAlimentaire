
USE Regime_db;

ALTER TABLE profil_sante
ADD COLUMN poids_souhaite DECIMAL(10,2) NULL AFTER taille,
ADD COLUMN activite ENUM('Faible', 'Moderee', 'Intense') DEFAULT 'Moderee' AFTER imc;