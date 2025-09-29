-- Suppression des tables existantes dans le bon ordre (pour éviter les problèmes de clés étrangères)
DROP TABLE IF EXISTS receptions_compteurs;
DROP TABLE IF EXISTS materiels_reception;
DROP TABLE IF EXISTS fiche_reception;

-- Création de la table fiche_reception
CREATE TABLE fiche_reception (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_fiche VARCHAR(50) NOT NULL UNIQUE,
    gie VARCHAR(100) NOT NULL,
    zone VARCHAR(50) NOT NULL,
    date_reception DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Création de la table materiels_reception
CREATE TABLE materiels_reception (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fiche_reception_id INT NOT NULL,  -- Changé de fiche_id à fiche_reception_id
    materiel_id INT NOT NULL,
    unite VARCHAR(20) NOT NULL,
    qte_constatee DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (fiche_reception_id) REFERENCES fiche_reception(id) ON DELETE CASCADE,
    FOREIGN KEY (materiel_id) REFERENCES materiel(id)
) ENGINE=InnoDB;

-- Création de la table receptions_compteurs
CREATE TABLE receptions_compteurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fiche_reception_id INT NOT NULL,  -- Changé de fiche_id à fiche_reception_id
    numero_compteur VARCHAR(50) NOT NULL UNIQUE,
    statut VARCHAR(20) DEFAULT 'actif',
    FOREIGN KEY (fiche_reception_id) REFERENCES fiche_reception(id) ON DELETE CASCADE
) ENGINE=InnoDB;