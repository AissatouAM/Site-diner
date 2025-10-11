-- db/site_diner.sql

CREATE DATABASE IF NOT EXISTS site_diner CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE site_diner;

-- Table des utilisateurs (participants/admin)
CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    numero VARCHAR(30) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    statut ENUM('participant', 'admin') DEFAULT 'participant'
);

-- Table des candidatures (pour Roi/Reine)
CREATE TABLE candidatures (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT,
    niveau_etude VARCHAR(50),
    photo VARCHAR(255),
    type ENUM('roi', 'reine'),
    date_candidature DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id),
    -- Empêcher qu'un même utilisateur ne se porte candidat plusieurs fois pour le même type
    UNIQUE KEY unique_candidate_per_type (utilisateur_id, type),
    INDEX idx_candidature_type (type),
    INDEX idx_candidature_utilisateur (utilisateur_id)
);

-- Table des votes
CREATE TABLE votes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    votant_id INT,
    candidature_id INT,
    -- Stocker le type du vote (roi/reine) pour pouvoir appliquer une contrainte d'unicité
    type ENUM('roi','reine') NOT NULL,
    date_vote DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (votant_id) REFERENCES utilisateurs(id),
    FOREIGN KEY (candidature_id) REFERENCES candidatures(id),
    -- Empêcher le même votant de voter plusieurs fois pour le même type (ex : 2 votes pour 'roi')
    UNIQUE KEY unique_vote_per_type (votant_id, type),
    INDEX idx_vote_votant (votant_id),
    INDEX idx_vote_candidature (candidature_id)
);
