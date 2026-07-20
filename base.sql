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
-- 3. OPERATEURS (nos opérateurs et les autres)
-- ---------------------------------------------------
CREATE TABLE operateurs (
    id       INTEGER PRIMARY KEY AUTOINCREMENT,
    nom      TEXT NOT NULL,
    est_nous INTEGER NOT NULL DEFAULT 0 CHECK (est_nous IN (0,1))
);

-- ---------------------------------------------------
-- 4. PREFIXES valides configurés par l'opérateur
-- ---------------------------------------------------
CREATE TABLE prefixes (
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe         TEXT NOT NULL UNIQUE,
    operateur_id    INTEGER REFERENCES operateurs(id),
    date_creation   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- ---------------------------------------------------
-- 5. TYPES D'OPERATION (dépôt, retrait, transfert)
-- ---------------------------------------------------
CREATE TABLE types_operation (
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    libelle         TEXT NOT NULL UNIQUE
);

-- ---------------------------------------------------
-- 6. TRANCHE_MONTANT : tranches de frais par type d'opération
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
-- 7. COMMISSIONS EXTERNES : % par opérateur externe
-- ---------------------------------------------------
CREATE TABLE commissions_externes (
    id             INTEGER PRIMARY KEY AUTOINCREMENT,
    operateur_id   INTEGER NOT NULL,
    pourcentage    REAL NOT NULL CHECK (pourcentage >= 0),
    date_creation  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (operateur_id) REFERENCES operateurs(id)
);

-- ---------------------------------------------------
-- 8. TRANSFERTS_GROUPES (envoi multiple)
-- ---------------------------------------------------
CREATE TABLE transferts_groupes (
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    client_id       INTEGER NOT NULL,
    montant_total   REAL NOT NULL,
    nb_destinataires INTEGER NOT NULL,
    date_operation  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE
);

-- ---------------------------------------------------
-- 9. TRANSACTIONS (historique + calcul des gains)
-- ---------------------------------------------------
CREATE TABLE transactions (
    id                          INTEGER PRIMARY KEY AUTOINCREMENT,
    client_id                   INTEGER NOT NULL,
    destinataire_id             INTEGER,
    type_operation_id           INTEGER NOT NULL,
    montant                     REAL NOT NULL CHECK (montant > 0),
    frais                       REAL NOT NULL DEFAULT 0,
    operateur_destinataire_id   INTEGER REFERENCES operateurs(id),
    commission_externe          REAL DEFAULT 0,
    groupe_id                   INTEGER REFERENCES transferts_groupes(id),
    date_operation              DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    FOREIGN KEY (destinataire_id) REFERENCES clients(id) ON DELETE SET NULL,
    FOREIGN KEY (type_operation_id) REFERENCES types_operation(id)
);


-- ======================================================
-- SEED DATA
-- ======================================================

-- Types d'opération
INSERT INTO types_operation (libelle) VALUES ('depot');
INSERT INTO types_operation (libelle) VALUES ('retrait');
INSERT INTO types_operation (libelle) VALUES ('transfert');

-- Opérateurs (1 = MoovMoney/nous, 2 = Airtel Money, 3 = Telma)
INSERT INTO operateurs (nom, est_nous) VALUES ('MoovMoney', 1);
INSERT INTO operateurs (nom, est_nous) VALUES ('Airtel Money', 0);
INSERT INTO operateurs (nom, est_nous) VALUES ('Telma', 0);

-- Préfixes valides
INSERT INTO prefixes (prefixe, operateur_id) VALUES ('033', 1);
INSERT INTO prefixes (prefixe, operateur_id) VALUES ('037', 1);
INSERT INTO prefixes (prefixe, operateur_id) VALUES ('032', 2);
INSERT INTO prefixes (prefixe, operateur_id) VALUES ('031', 3);

-- Commissions externes (uniquement opérateurs externes)
INSERT INTO commissions_externes (operateur_id, pourcentage) VALUES (2, 2.0);
INSERT INTO commissions_externes (operateur_id, pourcentage) VALUES (3, 1.5);

-- Barème de frais pour le RETRAIT (type_operation_id = 2)
INSERT INTO tranche_montant (type_operation_id, montant_min, montant_max, frais) VALUES
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

-- Barème de frais pour le TRANSFERT (type_operation_id = 3)
INSERT INTO tranche_montant (type_operation_id, montant_min, montant_max, frais) VALUES
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
