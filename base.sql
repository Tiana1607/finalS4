-- ---------------------------------------------------
-- 1. ADMINS (opérateur / back-office)
-- ---------------------------------------------------
CREATE TABLE admins (
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    nom             TEXT NOT NULL,
    email           TEXT NOT NULL UNIQUE,
    password        TEXT NOT NULL,         
    date_creation   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- ---------------------------------------------------
-- 2. CLIENTS (côté client, compte auto-créé au login)
-- ---------------------------------------------------
CREATE TABLE clients (
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    telephone       TEXT NOT NULL UNIQUE,
    solde           REAL NOT NULL DEFAULT 0,
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
-- ---------------------------------------------------
CREATE TABLE tranche_montant (
    id                  INTEGER PRIMARY KEY AUTOINCREMENT,
    type_operation_id   INTEGER NOT NULL,
    montant_min         REAL NOT NULL,
    montant_max         REAL NOT NULL,
    frais               REAL NOT NULL,
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

--  Types d'opération
INSERT INTO types_operation (libelle) VALUES ('depot');
INSERT INTO types_operation (libelle) VALUES ('retrait');
INSERT INTO types_operation (libelle) VALUES ('transfert');
 
-- Préfixes valides (exemple de l'énoncé)
INSERT INTO prefixes (prefixe) VALUES ('033');
INSERT INTO prefixes (prefixe) VALUES ('037');
 
-- Barème de frais pour le RETRAIT (type_operation_id = 2), d'après l'exemple du sujet
INSERT INTO baremes (type_operation_id, montant_min, montant_max, frais) VALUES
(2, 100,      1000,     50),
(2, 1001,     5000,     50),
(2, 5001,     10000,    100),
(2, 10001,    25000,    200),
(2, 25001,    50000,    400),
(2, 50001,    100000,   800),
(2, 100001,   250000,   1500),
(2, 250001,   500000,   1500),
(2, 500001,   1000000,  2500),
(2, 1000001,  2000000,  3000);
 
-- Barème de frais pour le TRANSFERT (type_operation_id = 3), même grille de départ
-- (à ajuster indépendamment côté admin, les deux barèmes sont désormais séparés)
INSERT INTO baremes (type_operation_id, montant_min, montant_max, frais) VALUES
(3, 100,      1000,     50),
(3, 1001,     5000,     50),
(3, 5001,     10000,    100),
(3, 10001,    25000,    200),
(3, 25001,    50000,    400),
(3, 50001,    100000,   800),
(3, 100001,   250000,   1500),
(3, 250001,   500000,   1500),
(3, 500001,   1000000,  2500),
(3, 1000001,  2000000,  3000);
 
-- Apport à la v2
 
CREATE TABLE operateurs (
    id       INTEGER PRIMARY KEY AUTOINCREMENT,
    nom      TEXT NOT NULL,
    est_nous INTEGER NOT NULL DEFAULT 0 CHECK (est_nous IN (0,1))
);

ALTER TABLE prefixes ADD COLUMN operateur_id INTEGER REFERENCES operateurs(id);

-- modification de la table transaction

ALTER TABLE transactions ADD COLUMN operateur_destinataire_id INTEGER REFERENCES operateurs(id);
ALTER TABLE transactions ADD COLUMN commission_externe REAL DEFAULT 0;

-- ALTER TABLE transactions ADD COLUMN frais_retrait_inclus INTEGER DEFAULT 0 CHECK (frais_retrait_inclus IN (0,1));
-- ALTER TABLE transactions ADD COLUMN montant_frais_retrait_couvert REAL DEFAULT 0;

CREATE TABLE transferts_groupes (
    id            INTEGER PRIMARY KEY AUTOINCREMENT,
    client_id     INTEGER NOT NULL,
    montant_total REAL NOT NULL,
    nb_destinataires INTEGER NOT NULL,
    date_operation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id)
);

ALTER TABLE transactions ADD COLUMN groupe_id INTEGER REFERENCES transferts_groupes(id);