USE Regime_db;

-- =====================================================
-- USERS : 5 utilisateurs + 1 admin
-- =====================================================

INSERT INTO users
    (nom, prenom, email, genre, mot_de_passe, solde, role, is_gold)
VALUES
    ('Rakoto',     'Jean',      'jean@gmail.com',      'Homme', '1234',      20000.00, 'USER',  FALSE),
    ('Rabe',       'Sarah',     'sarah@gmail.com',     'Femme', '1234',      36000.00, 'USER',  TRUE),
    ('Andry',      'Lucas',     'lucas@gmail.com',     'Homme', '1234',      10000.00, 'USER',  FALSE),
    ('Rasoanaivo', 'Emma',      'emma@gmail.com',      'Femme', '1234',      11750.00, 'USER',  TRUE),
    ('Koto',       'Mike',      'mike@gmail.com',      'Homme', '1234',          0.00, 'USER',  FALSE),
    ('Admin',      'Principal', 'admin@gmail.com',     'Autre', 'admin123',      0.00, 'ADMIN', FALSE);

-- =====================================================
-- PROFIL SANTE
-- =====================================================

INSERT INTO profil_sante
    (users_id, poids, taille, age, imc)
VALUES
    (1, 70.00, 1.75, 25, 22.86),
    (2, 90.00, 1.65, 30, 33.05),
    (3, 58.00, 1.80, 22, 17.90),
    (4, 95.00, 1.70, 35, 32.87),
    (5, 55.00, 1.68, 20, 19.49);

-- =====================================================
-- OBJECTIFS
-- =====================================================

INSERT INTO objectifs
    (nom, description)
VALUES
    ('Augmenter son poids',     'Objectif destiné aux utilisateurs qui veulent prendre du poids.'),
    ('Réduire son poids',       'Objectif destiné aux utilisateurs qui veulent perdre du poids.'),
    ('Atteindre son IMC idéal', 'Objectif destiné aux utilisateurs qui veulent atteindre un IMC normal.');

-- =====================================================
-- OBJECTIFS USERS
-- =====================================================

INSERT INTO objectifs_users
    (users_id, objectifs_id)
VALUES
    (1, 3),
    (2, 2),
    (3, 1),
    (4, 2),
    (5, 3);

-- =====================================================
-- REGIMES
-- =====================================================

INSERT INTO regimes
    (
        nom,
        description,
        objectifs_id,
        variation_poids,
        pourcentage_viande,
        pourcentage_poisson,
        pourcentage_volaille
    )
VALUES
    (
        'Régime Keto',
        'Régime faible en glucides pour favoriser la perte de poids.',
        2,
        -5.00,
        40.00,
        20.00,
        40.00
    ),
    (
        'Régime Hypercalorique',
        'Régime riche en calories pour favoriser la prise de masse.',
        1,
        4.00,
        50.00,
        10.00,
        40.00
    ),
    (
        'Régime Équilibré',
        'Régime équilibré pour maintenir un IMC normal.',
        3,
        0.00,
        30.00,
        40.00,
        30.00
    ),
    (
        'Régime Sportif',
        'Régime riche en protéines pour accompagner les activités sportives.',
        1,
        3.00,
        45.00,
        15.00,
        40.00
    ),
    (
        'Régime Minceur',
        'Régime hypocalorique pour réduire le poids.',
        2,
        -7.00,
        25.00,
        50.00,
        25.00
    );

-- =====================================================
-- REGIMES PRIX
-- =====================================================

INSERT INTO regimes_prix
    (regime_id, prix, duree_jours)
VALUES
    (1, 40000.00, 30),
    (1, 70000.00, 60),

    (2, 50000.00, 30),
    (2, 90000.00, 60),

    (3, 30000.00, 30),
    (3, 55000.00, 60),

    (4, 60000.00, 45),
    (4, 100000.00, 90),

    (5, 45000.00, 30),
    (5, 80000.00, 60);

-- =====================================================
-- REGIMES USERS
-- =====================================================

INSERT INTO regimes_users
    (users_id, regime_id, date_debut, date_fin)
VALUES
    (1, 3, '2026-05-01', '2026-05-31'),
    (2, 1, '2026-05-01', '2026-05-31'),
    (3, 2, '2026-05-05', '2026-06-04'),
    (4, 5, '2026-05-10', '2026-06-09'),
    (5, 3, '2026-05-15', '2026-06-14');

-- =====================================================
-- ACTIVITES SPORTIVES
-- =====================================================

INSERT INTO activite_sportive
    (nom, description, objectifs_id, duree_minutes)
VALUES
    ('Cardio',        'Activité pour brûler les graisses.',                 2, 45),
    ('Musculation',  'Activité pour augmenter la masse musculaire.',        1, 60),
    ('Marche rapide','Activité légère pour maintenir la forme.',            3, 30),
    ('Natation',     'Sport complet pour améliorer l’endurance.',           2, 50),
    ('Fitness',      'Activité générale pour améliorer la condition physique.', 3, 40);

-- =====================================================
-- ACTIVITES SPORTIVES USERS
-- =====================================================

INSERT INTO activite_sportive_users
    (users_id, activite_sportive_id, date_debut, date_fin)
VALUES
    (1, 3, '2026-05-01', '2026-05-31'),
    (2, 1, '2026-05-01', '2026-05-31'),
    (3, 2, '2026-05-05', '2026-06-04'),
    (4, 4, '2026-05-10', '2026-06-09'),
    (5, 5, '2026-05-15', '2026-06-14');

-- =====================================================
-- CODES PORTEFEUILLE : 15 codes
-- Quelques codes sont déjà utilisés
-- =====================================================

INSERT INTO portfeuille_code
    (code, description, utilise_le, used_by, utilise, montant)
VALUES
    ('CODE001', 'Recharge de 10 000 Ar',  NULL,         NULL, FALSE, 10000.00),
    ('CODE002', 'Recharge de 20 000 Ar',  NULL,         NULL, FALSE, 20000.00),
    ('CODE003', 'Recharge de 30 000 Ar',  NULL,         NULL, FALSE, 30000.00),
    ('CODE004', 'Recharge de 40 000 Ar',  NULL,         NULL, FALSE, 40000.00),
    ('CODE005', 'Recharge de 50 000 Ar',  '2026-05-01', 1,    TRUE,  50000.00),

    ('CODE006', 'Recharge de 60 000 Ar',  '2026-05-05', 3,    TRUE,  60000.00),
    ('CODE007', 'Recharge de 70 000 Ar',  NULL,         NULL, FALSE, 70000.00),
    ('CODE008', 'Recharge de 80 000 Ar',  NULL,         NULL, FALSE, 80000.00),
    ('CODE009', 'Recharge de 90 000 Ar',  NULL,         NULL, FALSE, 90000.00),
    ('CODE010', 'Recharge de 100 000 Ar', '2026-05-10', 4,    TRUE,  100000.00),

    ('CODE011', 'Recharge bonus 15 000 Ar', NULL,       NULL, FALSE, 15000.00),
    ('CODE012', 'Recharge promo 120 000 Ar','2026-05-01',2,   TRUE,  120000.00),
    ('CODE013', 'Recharge standard 35 000 Ar', NULL,    NULL, FALSE, 35000.00),
    ('CODE014', 'Recharge premium 45 000 Ar',  NULL,    NULL, FALSE, 45000.00),
    ('CODE015', 'Recharge de 30 000 Ar',  '2026-05-15', 5,    TRUE,  30000.00);

-- =====================================================
-- PORTEFEUILLE TRANSACTIONS
-- Recharge, achat régime, achat Gold
-- =====================================================

INSERT INTO portefeuille_transactions
    (user_id, regime_id, type, montant, description)
VALUES
    (1, NULL, 'RECHARGE',     50000.00,  'Recharge avec CODE005'),
    (1, 3,    'ACHAT_REGIME', -30000.00, 'Achat du Régime Équilibré'),

    (2, NULL, 'RECHARGE',     120000.00, 'Recharge avec CODE012'),
    (2, NULL, 'ACHAT_GOLD',   -50000.00, 'Activation de l’option Gold'),
    (2, 1,    'ACHAT_REGIME', -34000.00, 'Achat Régime Keto avec remise Gold de 15%'),

    (3, NULL, 'RECHARGE',     60000.00,  'Recharge avec CODE006'),
    (3, 2,    'ACHAT_REGIME', -50000.00, 'Achat du Régime Hypercalorique'),

    (4, NULL, 'RECHARGE',     100000.00, 'Recharge avec CODE010'),
    (4, NULL, 'ACHAT_GOLD',   -50000.00, 'Activation de l’option Gold'),
    (4, 5,    'ACHAT_REGIME', -38250.00, 'Achat Régime Minceur avec remise Gold de 15%'),

    (5, NULL, 'RECHARGE',     30000.00,  'Recharge avec CODE015'),
    (5, 3,    'ACHAT_REGIME', -30000.00, 'Achat du Régime Équilibré');

-- =====================================================
-- PARAMETRES
-- =====================================================

INSERT INTO parametres
    (cle, valeur)
VALUES
    ('prix_gold',        '50000'),
    ('reduction_gold',   '15'),
    ('imc_min_normal',   '18.5'),
    ('imc_max_normal',   '24.9'),
    ('devise',           'Ar');