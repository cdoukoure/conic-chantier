# 🏗️ Conic Chantier – Gestion de chantiers (Laravel + Docker + SecDevOps)

![CI](https://github.com/cdoukoure/conic-chantier/actions/workflows/ci.yml/badge.svg)


**Conic Chantier** est une application Laravel conçue pour gérer les projets, les contacts et les mouvements financiers d’un chantier.  
Ce projet sert de base à une plateforme DevOps-ready : intégration Docker, CI/CD GitHub Actions, sécurité et bonnes pratiques de développement.


## 🚀 Fonctionnalités clés

- 🔹 **Gestion de projets et sous-projets (chantiers)**
  - Création, édition, suppression
  - Association client
  - Gestion des budgets et périodes

- 🔹 **Suivi des contacts**
  - Différents types de contacts : client, ouvrier, fournisseur, prestataire, autre
  - Liaison aux projets ou chantiers
  - Ajout de rôles et de tarifs horaires

- 🔹 **Mouvements financiers**
  - Suivi des entrées et sorties
  - Association aux contacts
  - Catégorisation et justification des transactions
  - Gestion des paiements (espèces, virement, carte, chèque)

- 🔹 **Interface utilisateur fluide**
  - Intégration complète de **DataTables** (serveur + AJAX)
  - Formulaires modaux dynamiques (Bootstrap)
  - Feedback utilisateur via toasts/alertes

- 🔹 **Architecture MVC**
  - Séparation claire des responsabilités
  - API REST pour les opérations AJAX (CRUD asynchrone)

## 🧰 Stack technique
| Composant        | Technologie utilisée               |
|------------------|------------------------------------|
| Backend          | Laravel 10, PHP 8.2                |
| Serveur Web      | Nginx + PHP-FPM                    |
| Base de données  | MySQL 8                            |
| Frontend Tooling | Node.js + Vite                     |
| Conteneurisation | Docker + Docker Compose            |
| CI/CD            | GitHub Actions                     |
| Lint sécurité    | Hadolint, PHPStan, Trivy (Docker)  |


| Outil / Langage         | Usage                               |
|-------------------------|--------------------------------------|
| Laravel 10              | Framework PHP (Backend MVC)          |
| MySQL                   | Base de données relationnelle        |
| Bootstrap 5             | Framework CSS (UI)                   |
| DataTables.js (AJAX)    | Tableaux dynamiques interactifs      |
| JQuery                  | Manipulation DOM + AJAX              |
| Blade                   | Moteur de templates Laravel          |
| Composer                | Gestion des dépendances PHP          |
| Artisan CLI             | Génération de ressources & migration |



## 🛠️ Compétences techniques

- 📦 Environnement de développement avec **Docker et Docker Compose**
- ✅ Développement **full-stack Laravel**
- ✅ Gestion dynamique de contenu via **AJAX / DataTables**
- ✅ Conception de **relations Eloquent** (hasMany, belongsToMany, etc.)
- ✅ Implémentation de **modals dynamiques** pour un UX fluide
- ✅ Création d'**API internes RESTful** sécurisées par tokens CSRF
- ✅ Structuration propre du code (Controllers, Repositories, Routes)
- ✅ Déploiement sur hébergement mutualisé (ex : o2switch)

## 🏁 Installation 
```bash
git clone https://github.com/cdoukoure/conic-chantier.git
cd conic-chantier
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve

cp .env.example .env

docker compose up -d --build

docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed

```

## 🚀 Déploiement via SSH (CI/CD)
L’application est automatiquement déployée sur un serveur distant Linux via SSH grâce à GitHub Actions.

### 🧰 Pipeline CI/CD
Le pipeline effectue les étapes suivantes :

📦 Vérifie et teste le code Laravel (composer install, php artisan test)

🔐 Se connecte en SSH via une clé privée sécurisée

🛠️ Exécute les commandes de déploiement sur le serveur distant :
```bash
git pull

composer install --no-interaction --optimize-autoloader

php artisan migrate --force
php artisan config:cache, route:cache, view:cache

chown -R www-data:www-data .
```
### 🔑 Configuration GitHub
Des secrets sont utilisés pour sécuriser l’accès SSH :

Nom du Secret GitHub	Description
SSH_PRIVATE_KEY	Clé privée SSH sans mot de passe
SSH_HOST	Adresse IP du serveur distant
SSH_USER	Utilisateur distant (ex : ubuntu)
DEPLOY_PATH	Dossier distant (ex : /var/www/app)


## 🛡️ Sécurité
Le déploiement s’effectue sans mot de passe, via clé SSH privée stockée dans GitHub Secrets
L’accès est restreint à un utilisateur dédié au déploiement
Le pipeline ne fonctionne que sur la branche main

## ✅ Intégration Continue (GitHub Actions)

Le pipeline CI est exécuté automatiquement à chaque `push` ou `pull_request` sur la branche `main`.

### Il contient :

| Étape                   | Outil                  |
| ----------------------- | ---------------------- |
| 🔍 Lint Dockerfile      | Hadolint               |
| 🧠 Analyse statique PHP | PHPStan / Larastan     |
| 🔐 Scan image Docker    | Trivy (Aquasec)        |
| 🧪 Tests unitaires      | Laravel `artisan test` |
| 🧬 Validation MySQL     | Service intégré        |


---

## 🔐 Bonnes pratiques DevSecOps

* Docker multi-services (web, app, DB, node)
* Réseau isolé Docker
* Scan vulnérabilités image Docker (`trivy`)
* Analyse statique automatisée (`phpstan`)
* CI automatisée dès push
