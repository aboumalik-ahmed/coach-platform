# 🏃‍♂️ SPORTIFY - Plateforme Coach-Sportif

## 📋 Description du Projet

Sportify est une plateforme web de mise en relation entre sportifs et coachs sportifs professionnels. 
Cette première version permet de valider le concept avec des fonctionnalités essentielles.

## 🎯 Fonctionnalités

### Pour les Coachs:
- ✅ Inscription avec profil détaillé (discipline, expérience, description)
- ✅ Connexion sécurisée
- ✅ Gestion de profil
- ✅ Création de séances d'entraînement
- ✅ Modification et suppression de séances
- ✅ Consultation des réservations

### Pour les Sportifs:
- ✅ Inscription simple
- ✅ Connexion sécurisée
- ✅ Consultation de la liste des coachs
- ✅ Consultation des détails des coachs
- ✅ Visualisation des séances disponibles
- ✅ Réservation de séances
- ✅ Consultation de l'historique des réservations

### Fonctionnalités générales:
- ✅ Interface responsive et moderne
- ✅ Système de session sécurisé
- ✅ Validation des formulaires
- ✅ Page 404 personnalisée
- ✅ Architecture POO propre

## 🏗️ Architecture POO

### Principes appliqués:

1. **Encapsulation**: 
   - Propriétés privées/protégées dans toutes les classes
   - Accès via getters et setters

2. **Héritage**:
   - Classe de base `Utilisateur`
   - Classes filles `Coach` et `Sportif` qui héritent de `Utilisateur`

3. **Séparation des responsabilités**:
   - Classes métier (Coach, Sportif, Seance, Reservation)
   - Classe de connexion (Database)
   - Fichiers de configuration séparés

### Structure des classes:

```
Utilisateur (classe de base)
├── Coach (hérite de Utilisateur)
└── Sportif (hérite de Utilisateur)

Seance (classe indépendante)
Reservation (classe indépendante)
Database (classe de connexion)
```

## 📁 Structure du Projet

```
sportify/
│
├── classes/              # Classes PHP (POO)
│   ├── Utilisateur.php   # Classe de base
│   ├── Coach.php         # Classe Coach
│   ├── Sportif.php       # Classe Sportif
│   ├── Seance.php        # Classe Seance
│   └── Reservation.php   # Classe Reservation
│
├── config/               # Configuration
│   └── database.php      # Connexion BDD
│
├── includes/             # Fichiers réutilisables
│   ├── header.php        # En-tête du site
│   ├── footer.php        # Pied de page
│   └── session.php       # Gestion des sessions
│
├── pages/                # Pages de l'application
│   ├── login.php         # Connexion
│   ├── signup.php        # Inscription
│   ├── logout.php        # Déconnexion
│   ├── coachs.php        # Liste des coachs
│   ├── coach_detail.php  # Détail d'un coach
│   ├── seances.php       # Séances disponibles
│   ├── coach_dashboard.php    # Dashboard coach
│   ├── mes_reservations.php   # Réservations sportif
│   └── 404.php           # Page erreur 404
│
├── public/               # Fichiers publics
│   └── css/
│       └── style.css     # Styles CSS
│
├── database.sql          # Script SQL
├── README.md             # Ce fichier
└── index.php             # Page d'accueil
```

## 🚀 Installation

### Prérequis:
- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur
- Serveur web (Apache/Nginx) ou XAMPP/WAMP/MAMP

### Étapes d'installation:

1. **Cloner ou télécharger le projet**
   ```bash
   # Placer le dossier 'sportify' dans votre répertoire web
   # Par exemple: C:\xampp\htdocs\sportify
   ```

2. **Créer la base de données**
   - Ouvrir phpMyAdmin
   - Importer le fichier `database.sql`
   - Ou exécuter le script SQL manuellement

3. **Configurer la connexion à la base de données**
   - Ouvrir `config/database.php`
   - Modifier si nécessaire:
     ```php
     private $host = "localhost";
     private $db_name = "sportify_db";
     private $username = "root";
     private $password = "";
     ```

4. **Accéder à l'application**
   - Ouvrir votre navigateur
   - Aller à: `http://localhost/sportify`

## 👤 Comptes de Test

Le fichier `database.sql` contient des comptes de test:

### Coachs:
- **Email**: hassan.benali@sportify.com | **Mot de passe**: password
- **Email**: sara.alami@sportify.com | **Mot de passe**: password
- **Email**: karim.tahiri@sportify.com | **Mot de passe**: password
- **Email**: leila.mansouri@sportify.com | **Mot de passe**: password

### Sportifs:
- **Email**: amine.benjelloun@email.com | **Mot de passe**: password
- **Email**: fatima.idrissi@email.com | **Mot de passe**: password
- **Email**: omar.zemrani@email.com | **Mot de passe**: password

## 🔒 Sécurité

- ✅ Mots de passe hashés avec `password_hash()` (BCRYPT)
- ✅ Protection contre les injections SQL avec PDO et requêtes préparées
- ✅ Protection XSS avec `htmlspecialchars()`
- ✅ Validation des données côté serveur
- ✅ Gestion sécurisée des sessions

## 🎨 Technologies Utilisées

- **Backend**: PHP 7.4+ (Orienté Objet)
- **Base de données**: MySQL avec PDO
- **Frontend**: HTML5, CSS3 (Pure CSS, pas de framework)
- **Sécurité**: Sessions PHP, password_hash, requêtes préparées

## 📊 Diagramme de Classes UML (Bonus)

Voir le fichier `UML_Diagram.png` pour le diagramme de classes complet.

**Relations principales:**
- Coach hérite de Utilisateur
- Sportif hérite de Utilisateur
- Seance appartient à un Coach (1-N)
- Reservation lie une Seance et un Sportif (N-N)

## ✨ Fonctionnalités Bonus Implémentées

1. ✅ **Page 404 Personnalisée**: Page d'erreur élégante avec liens de navigation
2. ✅ **Dashboard Coach**: Interface complète avec statistiques
3. ✅ **Interface moderne**: Design responsive et professionnel
4. ✅ **Validation avancée**: Vérifications côté client et serveur
5. ✅ **Données de test**: Base de données pré-remplie

## 🔄 Évolutions Futures Possibles

- Système de notation des coachs
- Messagerie interne
- Paiement en ligne
- Calendrier interactif
- Filtres de recherche avancés
- Notifications par email
- Gestion d'avatar/photos
- Historique détaillé
- Export PDF des réservations

## 📝 Notes Techniques

### Validation des formulaires:
- Côté client: HTML5 (required, type, min, max)
- Côté serveur: PHP avec filtres et sanitization

### Gestion des erreurs:
- Try-catch pour les exceptions PDO
- Messages d'erreur utilisateur conviviaux
- Logs d'erreurs (à implémenter en production)

### Performance:
- Index sur les colonnes fréquemment requêtées
- Requêtes optimisées avec jointures
- Limitation des données retournées

## 👨‍💻 Auteur

Projet développé pour démontrer les compétences en:
- PHP Orienté Objet
- Architecture MVC simplifiée
- Sécurité web
- Design responsive
- Base de données relationnelles

## 📄 Licence

Ce projet est à usage éducatif.

---

**Version**: 1.0.0  
**Date**: Décembre 2024  
**Contact**: contact@sportify.com