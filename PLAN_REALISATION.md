# Plan de réalisation – RegimeAlimentaire

## 1) Livrables obligatoires (checklist)

- [ ] Formulaire de livraison Google Forms (URL à compléter)
- [ ] Lien code source GitHub/GitLab (URL à compléter)
- [ ] Script SQL base de données (fichier: `Database/06_05_2026.sql`)
- [ ] Liste des membres du groupe (section à compléter)
- [ ] Lien Google Sheet de suivi des tâches (URL à compléter)
- [ ] Historique de commits régulier (pas en fin de projet)
- [ ] Merge final vers `main`

## 2) État actuel du projet

### Déjà en place
- Authentification utilisateur (login/logout)
- Inscription en 2 étapes (base)
- Dashboard protégé par filtre auth
- Schéma SQL existant + seed

### Corrigé dans cette session
- Flux login aligné (front/back, routes, session)
- Bug `Undefined array key "password"` corrigé (`mot_de_passe`)
- Inscription étape 1 alignée avec les champs utilisateur (`nom`, `prenom`, `email`, `genre`, `date_naissance`)
- Rôle utilisateur remis en session au login (déblocage accès admin via rôle `ADMIN`)
- Achat de régime depuis le dashboard branché sur une route valide (`/regime/{id}/buy`)
- Statistiques dashboard réactivées (graphes simples + tableau de suivi)

## 3) Backlog priorisé (MVP -> complet)

## Sprint 1 — Auth + Profil minimal
- [x] Login robuste
- [x] Inscription en 2 pages fonctionnelle
- [x] Étape 2: enrichir profil santé (poids actuel, poids souhaité, activité)
- [x] Calcul IMC au moment de l’inscription
- [x] Sauvegarde profil santé cohérente (table dédiée)

## Sprint 2 — Objectifs + Suggestions (Front Office)
- [x] Choix d’un objectif (3 objectifs demandés)
- [x] Suggestion de régimes selon objectif
- [x] Suggestion activités sportives + durée
- [x] Estimation évolution poids

## Sprint 3 — Porte-monnaie + Gold
- [x] Consultation du solde
- [x] Recharge via code
- [x] Validation du code + historique
- [x] Achat option Gold
- [x] Application remise de 15% sur régimes
- [x] Achat de régime depuis les recommandations du dashboard

## Sprint 4 — Export PDF
- [x] Génération PDF récapitulatif utilisateur
- [x] Intégrer objectif/régime/activité/durée/prix

## Sprint 5 — Back Office
- [x] Login admin (admin existant utilisé via rôle `ADMIN` dans `users`)
- [x] Dashboard statistiques (graphes + tableaux)
- [x] CRUD régimes (prix selon durée + % viande/poisson/volaille)
- [x] CRUD activités sportives
- [x] Gestion/validation codes wallet
- [x] CRUD paramètres (IMC, Gold, remises, objectifs, durées)

## 4) Données minimales exigées

- [x] 5 utilisateurs
- [x] 15 codes
- [x] 5 régimes
- [x] 5 activités sportives

## 5) Workflow Git recommandé

- Branches:
  - `main` = référence
  - `feature/<module>` pour chaque fonctionnalité
- Commits fréquents:
  - 1 commit = 1 changement logique
  - Message explicite (`feat:`, `fix:`, `refactor:`)
- Merge régulier vers `main` après validation

## 6) Membres du groupe (à remplir)

- Membre 1:
- Membre 2:
- Membre 3:

## 7) Liens de livraison (à remplir)

- Google Forms:
- Dépôt Git:
- Google Sheet tâches:

## 8) Fin de projet / Recette finale

À faire à la toute fin du projet, après l’implémentation de toutes les fonctionnalités :

- [ ] Vérifier tous les parcours utilisateur en navigation réelle
- [ ] Tester le login, l’inscription, le dashboard, le PDF, le wallet et Gold
- [ ] Vérifier les codes portefeuille avec de vraies données de test
- [ ] Contrôler les données minimales exigées dans la base
- [ ] Faire une dernière revue UI/UX sur desktop et mobile
- [ ] Nettoyer le code, supprimer les doublons et corriger les derniers warnings
- [ ] Faire un commit final de stabilisation
- [ ] Merger la branche de travail dans `main`
- [ ] Faire le push final vers le dépôt Git
- [ ] Préparer les liens de livraison finaux
- [ ] Compléter le formulaire Google Forms de livraison
- [ ] Remplir la liste des membres du groupe
- [ ] Renseigner le lien Google Sheet de suivi des tâches
- [ ] Capturer les screenshots ou preuves de fonctionnement si demandé

comment on fait pour modifier les donne pour les utilisateur 