-- =====================================================
-- BASE DE DONNÉES SPORTIFY
-- Plateforme de mise en relation Coachs-Sportifs
-- =====================================================

-- Créer la base de données
CREATE DATABASE IF NOT EXISTS sportify_db;
USE sportify_db;

-- =====================================================
-- TABLE COACHS
-- =====================================================
CREATE TABLE IF NOT EXISTS coachs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    discipline VARCHAR(100) NOT NULL,
    annees_experience INT NOT NULL DEFAULT 0,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- =====================================================
-- TABLE SPORTIFS
-- =====================================================
CREATE TABLE IF NOT EXISTS sportifs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email)
);

-- =====================================================
-- TABLE SEANCES
-- =====================================================
CREATE TABLE IF NOT EXISTS seances (
    id INT AUTO_INCREMENT PRIMARY KEY,
    coach_id INT NOT NULL,
    date_seance DATE NOT NULL,
    heure TIME NOT NULL,
    duree INT NOT NULL COMMENT 'Durée en minutes',
    statut ENUM('disponible', 'reservee') DEFAULT 'disponible',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (coach_id) REFERENCES coachs(id) ON DELETE CASCADE
);

-- =====================================================
-- TABLE RESERVATIONS
-- =====================================================
CREATE TABLE IF NOT EXISTS reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    seance_id INT NOT NULL,
    sportif_id INT NOT NULL,
    date_reservation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (seance_id) REFERENCES seances(id) ON DELETE CASCADE,
    FOREIGN KEY (sportif_id) REFERENCES sportifs(id) ON DELETE CASCADE,
    UNIQUE KEY unique_reservation (seance_id, sportif_id)
);

-- =====================================================
-- DONNÉES DE TEST (OPTIONNEL)
-- =====================================================

-- Insertion de coachs de test
INSERT INTO coachs (nom, prenom, email, password, discipline, annees_experience, description) VALUES
('Benali', 'Hassan', 'hassan.benali@sportify.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Yoga', 5, 'Coach de yoga passionné avec 5 ans d\'expérience. Spécialisé dans le Hatha et Vinyasa yoga.'),
('Alami', 'Sara', 'sara.alami@sportify.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Musculation', 8, 'Coach sportif diplômé d\'État. Spécialiste en préparation physique et musculation.'),
('Tahiri', 'Karim', 'karim.tahiri@sportify.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Running', 6, 'Marathonien expérimenté et coach de course à pied. Préparation pour tous niveaux.'),
('Mansouri', 'Leila', 'leila.mansouri@sportify.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Pilates', 4, 'Instructrice certifiée en Pilates et renforcement musculaire.');

-- Insertion de sportifs de test
INSERT INTO sportifs (nom, prenom, email, password) VALUES
('Benjelloun', 'Amine', 'amine.benjelloun@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Idrissi', 'Fatima', 'fatima.idrissi@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Zemrani', 'Omar', 'omar.zemrani@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Insertion de séances de test
INSERT INTO seances (coach_id, date_seance, heure, duree, statut) VALUES
(1, '2025-12-26', '09:00:00', 60, 'disponible'),
(1, '2025-12-27', '10:00:00', 60, 'disponible'),
(2, '2025-12-26', '14:00:00', 90, 'disponible'),
(2, '2025-12-28', '15:00:00', 90, 'disponible'),
(3, '2025-12-27', '07:00:00', 45, 'disponible'),
(3, '2025-12-29', '07:00:00', 45, 'disponible'),
(4, '2025-12-26', '11:00:00', 60, 'disponible'),
(4, '2025-12-30', '16:00:00', 60, 'disponible');

-- =====================================================
-- INFORMATIONS IMPORTANTES
-- =====================================================
-- Mot de passe par défaut pour tous les comptes test: "password"
-- 
-- Comptes Coach:
-- - hassan.benali@sportify.com / password
-- - sara.alami@sportify.com / password
-- - karim.tahiri@sportify.com / password
-- - leila.mansouri@sportify.com / password
--
-- Comptes Sportif:
-- - amine.benjelloun@email.com / password
-- - fatima.idrissi@email.com / password
-- - omar.zemrani@email.com / password
-- =====================================================
