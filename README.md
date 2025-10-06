# ProgServ2_Library-Project

Project done at HEIG-VD - ProgServ 2
 
## 👨‍💻 Contributors:  
- Liliana Kolmakova
- Thierry Koulbanis
 
## 📜 Description  
 
| Feature               | Description                                                                                     |
|-----------------------|-------------------------------------------------------------------------------------------------|
| **Books Manager** | Add and remove a book on a private library. |
| **Sorting and Filtering** | Sort and filter books by name, writer or genre. |

## 📌 Cahier des charges

<details>
 <summary>Cahier des charges</summary>

## 1. Objectif du projet
Le projet **Libs Project** a pour but de créer une application web simple et pratique qui permet à chacun de gérer sa propre bibliothèque en ligne. 
L’idée est d’offrir un espace personnel où l’utilisateur peut ajouter ses livres, suivre ses lectures, laisser des notes ou des commentaires, et organiser sa collection selon ses envies.
L’application sera intuitive, accessible depuis Internet, et développée avec PHP et MySQL.

L’application sera intuitive, accessible depuis Internet, et développée avec PHP et MySQL.

## 2. Description générale
Le site proposera une interface claire avec deux types d’accès :
- un espace public (pour la présentation du projet, l’inscription et la connexion)
- un espace privé (réservé aux utilisateurs connectés)

Deux rôles d’utilisateur seront prévus :
- **Utilisateur standard**, qui gère uniquement sa propre bibliothèque
- **Administrateur**, qui peut en plus gérer les comptes utilisateurs et modérer le contenu

## 3. Fonctionnalités principales

**Gestion des utilisateurs**
- Création d’un compte via un formulaire d’inscription
- Envoi automatique d’un e-mail de confirmation après la création du compte
- Connexion et déconnexion avec gestion de session
- Sécurité assurée avec des mots de passe hashés
- Gestion de deux rôles : utilisateur et administrateur

**Gestion des livres**

- Ajouter un livre avec ses informations : titre, auteur, genre, année, ISBN (optionnel)
- Modifier ou supprimer un livre existant
- Afficher la liste de ses livres
- Rechercher un livre par mot-clé
- Trier les livres par titre, auteur, genre ou année
  
**Suivi de lecture**
  
- Marquer un livre comme à lire, en cours ou lu
- Donner une note (de 1 à 5 étoiles)
- Laisser un commentaire personnel sur la lecture

## 4. Fonctionnalités optionnelles (si le temps le permet)
- Ajouter une image de couverture personnalisée
- Possibilité de scanner le ISBN

## 5. Structure des pages

| Type        | Page              | Description                                                                 |
|-------------|-------------------|-----------------------------------------------------------------------------|
| Publique   | Accueil           | Présente le projet et ses objectifs, avec accès à l’inscription et à la connexion |
| Publique   | Inscription       | Formulaire de création de compte                                            |
| Publique   | Connexion         | Formulaire d’accès à son compte                                             |
| Privée     | Tableau de bord   | Vue d’ensemble de la bibliothèque personnelle                               |
| Privée     | Mes livres        | Liste complète avec recherche et tri                                       |
| Privée     | Ajouter un livre  | Formulaire d’ajout de nouveau livre                                        |
| Privée     | Détails du livre  | Informations complètes, note et commentaire, options de modification       |
| Privée     | Administration    | Espace réservé à l’administrateur pour gérer les utilisateurs              |

## 6. Modèle de données

**Table : utilisateurs**

| Champ          | Description                              |
|---------------|------------------------------------------|
| id            | Identifiant unique                       |
| nom           | Nom de l’utilisateur                     |
| email         | Adresse e-mail                           |
| mot_de_passe  | Mot de passe hashé                       |
| role          | “user” ou “admin”                        |
| date_creation | Date d’inscription                       |

**Table : livres**

| Champ           | Description                                 |
|-----------------|---------------------------------------------|
| id              | Identifiant unique                          |
| titre           | Titre du livre                              |
| auteur          | Auteur                                      |
| genre           | Genre littéraire                            |
| annee           | Année de publication                        |
| isbn            | Numéro ISBN (facultatif)                    |
| couverture_url  | Image de couverture (facultative)          |
| utilisateur_id  | Lien vers le propriétaire du livre          |

**Table : lectures**

| Champ         | Description                                      |
|--------------|--------------------------------------------------|
| id           | Identifiant unique                               |
| utilisateur_id | Lien vers l’utilisateur                         |
| livre_id     | Lien vers le livre                                |
| statut       | “à lire”, “en cours”, ou “lu”                     |
| note         | Note sur 5                                       |
| commentaire  | Texte libre                                      |

## 7. Aspects techniques

- **Langages :** PHP, HTML, CSS
- **Base de données :** MySQL/MariaDB
- **Architecture :** code organisé en PHP orienté objet (modèle MVC simplifié)
- **Sécurité :**
  - Hash des mots de passe (password_hash())
  - Requêtes SQL sécurisées (PDO + requêtes préparées)
  - Validation et nettoyage des données utilisateur
- **Déploiement :** hébergement sur un serveur public
- **Configuration :** infos de connexion à la base dans un fichier séparé (config.php)

## 8. Répartition du travail

## Répartition des responsabilités

| Membre   | Responsabilités principales                                                                                  |
|----------|--------------------------------------------------------------------------------------------------------------|
| Liliana  | Design et intégration HTML/CSS, pages publiques, gestion multilingue, structure front-end                    |
| Thierry  | Base de données, authentification, sessions, gestion des rôles, pages privées                                |
| Tous deux | Conception du modèle de données, tests, documentation et déploiement                                        |

## 9. Conclusion
Libs Project est une application web qui aide à gérer sa bibliothèque personnelle. 

Elle propose une interface simple et une base solide : authentification, gestion des rôles, sécurité, sessions et base de données. Le but est de créer un site fonctionnel et facile à utiliser, tout en mettant en pratique ce qu’on a appris en cours. Ce projet permet de voir concrètement comment fonctionne une application web complète, depuis le développement jusqu’au déploiement.
</details>

## 
