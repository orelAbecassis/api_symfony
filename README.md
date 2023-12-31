# API Symfony

Cette API a été développée avec Symfony, un framework PHP populaire pour le développement d'applications web. Elle fournit des fonctionnalités pour gérer des produits et des catégories.

# Fonctionnalités
L'API Symfony offre les fonctionnalités suivantes :

<b>Produits <b>: Vous pouvez effectuer les opérations CRUD (Create, Read, Update, Delete) sur les produits. Cela comprend la création d'un produit, la récupération d'une liste de produits, la récupération des détails d'un produit spécifique, la mise à jour d'un produit et la suppression d'un produit.

<b>Catégories <b>: Vous pouvez également effectuer les opérations CRUD sur les catégories. Cela comprend la création d'une catégorie, la récupération d'une liste de catégories, la récupération des détails d'une catégorie spécifique, la mise à jour d'une catégorie et la suppression d'une catégorie.

## Installation
Pour installer et exécuter l'API Symfony, suivez les étapes ci-dessous :

Assurez-vous d'avoir PHP et Composer installés sur votre machine.

Clonez ce dépôt sur votre machine locale.

````bash 
git clone git@github.com:orelAbecassis/api_symfony.git
````
Accédez au répertoire du projet.

````bash 
cd api-symfony
````

Installez les dépendances à l'aide de Composer.

```` bash
composer install
````

Configurez la base de données dans le fichier .env en spécifiant les informations de connexion à votre base de données.

Configuration de la base de donnée <br>
HOST=127.0.0.1 # De base le port 3306 est préciser <br>
DB_USER=root <br>
DB_PASS= <br>
DB_DATABASE=blogApi <br>

Exécutez les migrations pour créer les tables de la base de données.

```` bash
symfony console doctrine:migrations:migrate

````
Lancez le serveur de développement.

```` bash
symfony serve

````
 L'API Symfony est maintenant prête à être utilisée !

# Endpoints
L'API expose les endpoints suivants : <br>

GET /api/produits: Récupère la liste des produits. <br>

GET /api/produits/{id}: Récupère les détails d'un produit spécifique. <br>

POST /api/produits: Crée un nouveau produit. <br>

PUT /api/produits/{id}: Met à jour un produit existant. <br>

DELETE /api/produits/{id}: Supprime un produit. <br>

GET /api/categories: Récupère la liste des catégories. <br>

GET /api/categories/{id}: Récupère les détails d'une catégorie spécifique. <br>

POST /api/categories: Crée une nouvelle catégorie. <br>

PUT /api/categories/{id}: Met à jour une catégorie existante. <br>

DELETE /api/categories/{id}: Supprime une catégorie. <br>


# Authentification
L'API Symfony utilise un système d'authentification basé sur les rôles. Deux rôles sont disponibles :

ROLE_USER: Un utilisateur avec ce rôle a des autorisations limitées pour accéder à certaines fonctionnalités de l'API. <br>
ROLE_ADMIN: Un utilisateur avec ce rôle a des autorisations étendues et peut accéder à toutes les fonctionnalités de l'API. <br>
