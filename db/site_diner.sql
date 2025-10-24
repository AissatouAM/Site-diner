-- db/site_diner.sql 
 
CREATE DATABASE IF NOT EXISTS site_diner CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci; 
USE site_diner; 
 
-- Drop tables si elles existent (pratique pour réimport) 
DROP TABLE IF EXISTS `votes`; 
DROP TABLE IF EXISTS `candidats`; 
DROP TABLE IF EXISTS `utilisateurs`; 
 
-- Table utilisateur 
CREATE TABLE `utilisateurs` ( 
    `id_utilisateur` INT AUTO_INCREMENT PRIMARY KEY, 
    `nom` VARCHAR(100) NOT NULL,
    `prenom` VARCHAR(100) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `telephone` VARCHAR(30) NOT NULL UNIQUE, 
    `mot_de_passe` VARCHAR(255) NOT NULL, 
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 
 
-- Table candidat 
CREATE TABLE `candidats` ( 
    `id_candidat` INT AUTO_INCREMENT PRIMARY KEY, 
    `nom` VARCHAR(100) NOT NULL, 
    `prenom` VARCHAR(100) NOT NULL, 
    -- téléphone du candidat (référence optionnelle vers table utilisateur) 
    `telephone` VARCHAR(30) DEFAULT NULL, 
    `photo` VARCHAR(255) DEFAULT NULL, 
    `niveau` VARCHAR(100) DEFAULT NULL, 
    `genre_candidat` ENUM('masculin','feminin') NOT NULL, 
    -- champ de comptage (facultatif, peut être maintenu via trigger ou requête d'agrégation) 
    `vote` INT DEFAULT 0, 
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP, 
    INDEX (`genre_candidat`), 
    INDEX (`telephone`), 
    CONSTRAINT `fk_candidat_telephone` FOREIGN KEY (`telephone`) REFERENCES `utilisateurs`(`telephone`) ON DELETE SET NULL ON UPDATE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 
 
-- Table vote 
CREATE TABLE `votes` ( 
    `id` INT AUTO_INCREMENT PRIMARY KEY, 
    `id_utilisateur` INT NOT NULL, 
    `id_candidat` INT NOT NULL, 
    `genre_candidat` ENUM('masculin','feminin') NOT NULL, 
    `date_vote` DATETIME DEFAULT CURRENT_TIMESTAMP, 
    CONSTRAINT `fk_vote_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs`(`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE, 
    CONSTRAINT `fk_vote_candidat` FOREIGN KEY (`id_candidat`) REFERENCES `candidats`(`id_candidat`) ON DELETE CASCADE ON UPDATE CASCADE, 
    -- Contrainte: un utilisateur ne peut voter qu'une fois par genre 
    UNIQUE KEY `unique_vote_per_genre` (`id_utilisateur`, `genre_candidat`), 
    INDEX (`id_utilisateur`), 
    INDEX (`id_candidat`) 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

ALTER TABLE `utilisateurs` ADD `role` ENUM('user', 'admin') NOT NULL DEFAULT 'user' AFTER `mot_de_passe`;

ALTER TABLE `candidats` ADD `status` ENUM('approved', 'pending', 'rejected') NOT NULL DEFAULT 'approved' AFTER `vote`;

ALTER TABLE utilisateurs
ADD COLUMN IF NOT EXISTS reset_token VARCHAR(64) NULL,
ADD COLUMN IF NOT EXISTS token_expire DATETIME NULL;


 
-- Note: la colonne `vote` dans `candidat` est un champ de cache; il peut être mis à jour 
-- via trigger ou recalculé par une requête d'agrégation : 
-- SELECT id_candidat, COUNT(*) FROM votes GROUP BY id_candidat;