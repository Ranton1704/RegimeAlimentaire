# Todolist

## Front-Office

### 1. Authentification utilisateur
Fonctionnalités :
- Création de compte utilisateur
- Connexion utilisateur
- Déconnexion utilisateur
- Gestion des erreurs de connexion
- Validation des champs obligatoires

### 2. Inscription en 2 étapes
Étape 1 : Informations utilisateur
- Nom
- Prénom
- Email
- Mot de passe
- Genre
- Date de naissance

Étape 2 : Informations de santé
- Taille
- Poids actuel
- Poids souhaité
- Calcul automatique de l’IMC
- Niveau d’activité physique

### 3. Gestion du profil utilisateur
Fonctionnalités :
- Modifier les informations personnelles
- Modifier les informations de santé
- Afficher le profil complet
- Afficher l’IMC actuel
- Afficher les objectifs sélectionnés

### 4. Gestion des objectifs
Objectifs disponibles :
- Augmenter son poids
- Réduire son poids
- Atteindre son IMC idéal

### 5. Suggestion intelligente des régimes
Fonctionnalités :
- Suggestion automatique des régimes selon l’objectif
- Suggestion des activités sportives adaptées
- Définition de la durée du programme
- Estimation de l’évolution du poids
- Calcul automatique du coût du régime

### 6. Exportation PDF
Fonctionnalités :
- Exporter en PDF les informations suivantes :
	- Informations utilisateur
	- Informations de santé
	- Objectif choisi
	- Régime recommandé
	- Activités sportives recommandées
	- Durée du programme
	- Prix total

### 7. Porte-monnaie utilisateur
Fonctionnalités :
- Afficher le solde du portefeuille
- Ajouter de l’argent avec un code
- Vérifier la validité du code
- Consulter l’historique des recharges

### 8. Option Gold
Fonctionnalités :
- Achat d’un abonnement Gold
- Paiement unique
- Affichage du statut Gold

Avantages :
- Réduction automatique de 15 % sur tous les régimes

Exemple :
- Prix proposé : 50 000 Ar

## Back-Office

### 1. Authentification administrateur
Fonctionnalités :
- Page de connexion administrateur
- Sécurisation des accès
- Gestion des sessions administrateur

### 2. Dashboard administrateur
Statistiques :
- Nombre total d’utilisateurs
- Nombre d’utilisateurs Gold
- Nombre de régimes achetés
- Revenus générés
- Nombre de codes utilisés

Visualisations :
- Graphiques statistiques
- Tableaux croisés
- Évolution des inscriptions
- Répartition des objectifs

Fonctionnalités Back-Office :

### 3. CRUD des régimes
Fonctionnalités :
- Ajouter un régime
- Modifier un régime
- Supprimer un régime
- Afficher la liste des régimes

Informations d’un régime :
- Nom du régime
- Description
- Prix
- Durée
- Variation de poids
- Objectif associé

Composition alimentaire :
- Pourcentage de viande
- Pourcentage de poisson
- Pourcentage de volaille

Gestion des prix :
- Prix variable selon la durée

### 4. CRUD des activités sportives
Fonctionnalités :
- Ajouter une activité sportive
- Modifier une activité sportive
- Supprimer une activité sportive
- Afficher les activités sportives

Informations d’une activité :
- Nom
- Description
- Calories brûlées
- Durée recommandée
- Objectif associé

### 5. Gestion des codes porte-monnaie
Fonctionnalités :
- Générer des codes
- Définir le montant du code
- Valider les codes
- Désactiver les codes expirés
- Voir l’historique des utilisations

### 6. CRUD des paramètres
Paramètres gérables :
- Paramètres IMC
- Prix de l’option Gold
- Taux de réduction
- Objectifs disponibles
- Durées des régimes