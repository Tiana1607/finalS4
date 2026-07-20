-- =====================================================
-- Schéma BDD - Projet Mobile Money (CodeIgniter 4 + SQLite)
-- =====================================================

-- ---------------------------------------------------
-- 1. ADMINS (opérateur / back-office)
-- ---------------------------------------------------
CREATE TABLE admins (
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    nom             TEXT NOT NULL,
    email           TEXT NOT NULL UNIQUE,
    mot_de_passe    TEXT NOT NULL,          -- haché avec password_hash()
    date_creation   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- ---------------------------------------------------
-- 2. CLIENTS (côté client, compte auto-créé au login)
-- ---------------------------------------------------
CREATE TABLE clients (
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    telephone       TEXT NOT NULL UNIQUE,
    solde           REAL NOT NULL DEFAULT 0 CHECK (solde >= 0),
    date_creation   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- ---------------------------------------------------
-- 3. PREFIXES valides configurés par l'opérateur
-- ---------------------------------------------------
CREATE TABLE prefixes (
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe         TEXT NOT NULL UNIQUE,   -- ex: "033", "037"
    date_creation   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- ---------------------------------------------------
-- 4. TYPES D'OPERATION (dépôt, retrait, transfert)
-- ---------------------------------------------------
CREATE TABLE types_operation (
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    libelle         TEXT NOT NULL UNIQUE    -- 'depot' | 'retrait' | 'transfert'
);

-- ---------------------------------------------------
-- 5. tranche_montant : tranches de frais par type d'opération
--    (modifiable par l'opérateur)
-- ---------------------------------------------------
CREATE TABLE tranche_montant (
    id                  INTEGER PRIMARY KEY AUTOINCREMENT,
    type_operation_id   INTEGER NOT NULL,
    montant_min         REAL NOT NULL,
    montant_max         REAL NOT NULL,
    frais               REAL NOT NULL,
    CHECK (montant_max > montant_min),
    CHECK (frais >= 0),
    FOREIGN KEY (type_operation_id) REFERENCES types_operation(id) ON DELETE CASCADE
);

-- ---------------------------------------------------
-- 6. TRANSACTIONS (historique + calcul des gains)
-- ---------------------------------------------------
CREATE TABLE transactions (
    id                  INTEGER PRIMARY KEY AUTOINCREMENT,
    client_id           INTEGER NOT NULL,       -- expéditeur / initiateur
    destinataire_id     INTEGER,                -- rempli uniquement pour un transfert
    type_operation_id   INTEGER NOT NULL,
    montant             REAL NOT NULL CHECK (montant > 0),
    frais                REAL NOT NULL DEFAULT 0,
    date_operation      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    FOREIGN KEY (destinataire_id) REFERENCES clients(id) ON DELETE SET NULL,
    FOREIGN KEY (type_operation_id) REFERENCES types_operation(id)
);

-- Index utiles pour les recherches fréquentes (historique, calcul du bon barème)
CREATE INDEX idx_transactions_client ON transactions(client_id);
CREATE INDEX idx_transactions_type ON transactions(type_operation_id);
CREATE INDEX idx_tranche_montant_type ON tranche_montant(type_operation_id);

