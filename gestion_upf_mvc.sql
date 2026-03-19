
DROP DATABASE IF EXISTS gestion_upf_mvc;
CREATE DATABASE gestion_upf_mvc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gestion_upf_mvc;

-- ============================================================
-- TABLE : filieres
-- ============================================================
CREATE TABLE filieres (
    CodeF       VARCHAR(10)  NOT NULL,
    IntituleF   VARCHAR(100) NOT NULL,
    responsable VARCHAR(100) DEFAULT NULL,
    nbPlaces    INT          DEFAULT NULL,
    created_at  DATETIME     NOT NULL,
    PRIMARY KEY (CodeF)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABLE : etudiants
-- ============================================================
CREATE TABLE etudiants (
    Code           VARCHAR(10)  NOT NULL,
    Nom            VARCHAR(50)  NOT NULL,
    Prenom         VARCHAR(50)  NOT NULL,
    Filiere        VARCHAR(10)  DEFAULT NULL,
    Note           DECIMAL(4,2) DEFAULT NULL,
    Photo          VARCHAR(255) DEFAULT NULL,
    date_naissance DATE         DEFAULT NULL,
    email          VARCHAR(100) DEFAULT NULL,
    telephone      VARCHAR(20)  DEFAULT NULL,
    created_at     DATETIME     NOT NULL,
    PRIMARY KEY (Code),
    UNIQUE KEY email (email),
    CONSTRAINT fk_etudiant_filiere FOREIGN KEY (Filiere)
        REFERENCES filieres(CodeF) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABLE : utilisateurs
-- ============================================================
CREATE TABLE utilisateurs (
    id                  INT          NOT NULL AUTO_INCREMENT,
    login               VARCHAR(50)  NOT NULL,
    password            VARCHAR(255) NOT NULL,
    role                ENUM('admin','user') NOT NULL,
    etudiant_id         VARCHAR(10)  DEFAULT NULL,
    derniere_connexion  DATETIME     DEFAULT NULL,
    created_at          DATETIME     NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY login (login),
    CONSTRAINT fk_user_etudiant FOREIGN KEY (etudiant_id)
        REFERENCES etudiants(Code) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABLE : documents
-- ============================================================
CREATE TABLE documents (
    id          INT          NOT NULL AUTO_INCREMENT,
    etudiant_id VARCHAR(10)  NOT NULL,
    type_doc    ENUM('releve_notes','attestation','autre') NOT NULL,
    nom_fichier VARCHAR(255) NOT NULL,
    chemin      VARCHAR(255) NOT NULL,
    taille      INT          NOT NULL,
    mime_type   VARCHAR(100) NOT NULL,
    uploaded_by INT          NOT NULL,
    uploaded_at DATETIME     NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_doc_etudiant FOREIGN KEY (etudiant_id)
        REFERENCES etudiants(Code) ON DELETE CASCADE,
    CONSTRAINT fk_doc_admin FOREIGN KEY (uploaded_by)
        REFERENCES utilisateurs(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



CREATE TABLE IF NOT EXISTS filieres_documents (
    id          INT          NOT NULL AUTO_INCREMENT,
    filiere_id  VARCHAR(10)  NOT NULL,
    type_doc    ENUM('emploi_temps','programme','circulaire','autre') NOT NULL DEFAULT 'autre',
    titre       VARCHAR(200) NOT NULL,
    nom_fichier VARCHAR(255) NOT NULL,
    chemin      VARCHAR(255) NOT NULL,
    taille      INT          NOT NULL,
    mime_type   VARCHAR(100) NOT NULL,
    uploaded_by INT          NOT NULL,
    uploaded_at DATETIME     NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_fildoc_filiere FOREIGN KEY (filiere_id)
        REFERENCES filieres(CodeF) ON DELETE CASCADE,
    CONSTRAINT fk_fildoc_admin FOREIGN KEY (uploaded_by)
        REFERENCES utilisateurs(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- DONNÉES DE DÉMONSTRATION (filieres + etudiants pour les comptes user)
-- ============================================================
INSERT INTO filieres (CodeF, IntituleF, responsable, nbPlaces, created_at) VALUES
('GINFO', 'Génie Informatique', 'Responsable GINFO', 60, NOW());

INSERT INTO etudiants (Code, Nom, Prenom, Filiere, Note, created_at) VALUES
('E001', 'Bouhsini', 'Aya', 'GINFO', 18.50, NOW()),
('E002', 'Boutarfass', 'Kenza', 'GINFO', 18.00, NOW()),
('E003', 'El mzaiti', 'Ghizlane', 'GINFO', 18.75, NOW()),
('E004', 'El moumen', 'aicha', 'GINFO', 18.00, NOW()),
('E005', 'El Fassi', 'Youssef', 'GINFO', 13.25, NOW());

-- ============================================================
-- COMPTES DE TEST (admin + user)
-- Mot de passe en clair :
--   admin : admin123
--   user  : user12345
-- ============================================================
INSERT INTO utilisateurs (login, password, role, etudiant_id, created_at) VALUES
('admin', '$2y$10$DPJpCWFS0TuPEbFfGAcZSuzYiaH/mn4.qsylobtxwJllrvTG5bdNu', 'admin', NULL, NOW()),
('e001',  '$2y$10$WkptnmvmUDZKhMWG5l589uuvBEFGADuIqT.9pbx/o5f1SU7YU717y', 'user',  'E001', NOW());
