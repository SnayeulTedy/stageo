CREATE DATABASE stageo_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE stageo_db;

-- Table des Entreprises
CREATE TABLE entreprises (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    secteur VARCHAR(100),
    ville VARCHAR(100),
    email VARCHAR(100),
    telephone VARCHAR(20),
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table des Offres
CREATE TABLE offres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(150) NOT NULL,
    entreprise_id INT,
    type ENUM('Stage', 'Alternance') NOT NULL,
    duree VARCHAR(50),
    niveau VARCHAR(50),
    description TEXT,
    date_publication DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (entreprise_id) REFERENCES entreprises(id)
);

-- Table des Candidatures
CREATE TABLE candidatures (
    id INT AUTO_INCREMENT PRIMARY KEY,
    offre_id INT,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telephone VARCHAR(20),
    message TEXT,
    cv_nom VARCHAR(255),
    lettre_nom VARCHAR(255),
    statut ENUM('En cours', 'En attente', 'Accepté', 'Refusé') DEFAULT 'En cours',
    date_candidature DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (offre_id) REFERENCES offres(id)
);
