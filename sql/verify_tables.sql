-- Vérification et création des tables nécessaires
CREATE TABLE IF NOT EXISTS fiche_reception (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_fiche VARCHAR(50) NOT NULL UNIQUE,
    gie VARCHAR(100) NOT NULL,
    zone VARCHAR(50) NOT NULL,
    date_reception DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS materiels_reception (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fiche_id INT NOT NULL,
    materiel_id INT NOT NULL,
    unite VARCHAR(20) NOT NULL,
    qte_constatee DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (fiche_id) REFERENCES fiche_reception(id) ON DELETE CASCADE,
    FOREIGN KEY (materiel_id) REFERENCES materiel(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS receptions_compteurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fiche_id INT NOT NULL,
    numero_compteur VARCHAR(50) NOT NULL UNIQUE,
    statut VARCHAR(20) DEFAULT 'actif',
    FOREIGN KEY (fiche_id) REFERENCES fiche_reception(id) ON DELETE CASCADE
) ENGINE=InnoDB;