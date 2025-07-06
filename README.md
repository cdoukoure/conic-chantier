# 🏗️ Laravel Project Manager

![CI](https://github.com/cdoukoure/conic-chantier/actions/workflows/ci.yml/badge.svg)



Ce projet est une application web développée avec **Laravel 10** destinée à la gestion de projets, chantiers, contacts et mouvements financiers. Il s'adresse à toute organisation souhaitant suivre efficacement l'avancement de ses projets et la traçabilité financière associée.

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

## 🛠️ Compétences démontrées

- 📦 Environnement de développement avec **Docker et Docker Compose**
- ✅ Développement **full-stack Laravel**
- ✅ Gestion dynamique de contenu via **AJAX / DataTables**
- ✅ Conception de **relations Eloquent** (hasMany, belongsToMany, etc.)
- ✅ Implémentation de **modals dynamiques** pour un UX fluide
- ✅ Création d'**API internes RESTful** sécurisées par tokens CSRF
- ✅ Structuration propre du code (Controllers, Repositories, Routes)
- ✅ Déploiement sur hébergement mutualisé (ex : o2switch)

## 🏁 Installation locale

```bash
git clone https://github.com/cdoukoure/conic-chantier.git
cd nom-du-repo
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
