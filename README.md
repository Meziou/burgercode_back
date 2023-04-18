# burgercode_back

## Projet API REST Symfony ORM
Ce projet est une API REST créée avec Symfony et utilisant l'ORM Doctrine pour interagir avec une base de données relationnelle. Il contient les entités "category", "items" et "picture", ainsi que leurs contrôleurs correspondants.

## Configuration requise

PHP 7.4 ou version ultérieure
Symfony CLI 4.26.3 ou version ultérieure
Composer


## Installation
1. Clonez ce dépôt sur votre machine locale :
git clone https://github.com/votre_nom_de_utilisateur/votre_projet.git

2. Installez les dépendances du projet avec Composer :
composer install

3. Configurez les variables d'environnement dans le fichier .env.local pour la connexion à votre base de données.

4. Créez la base de données et les tables nécessaires :
php bin/console doctrine:database:create
php bin/console doctrine:schema:create

5. Chargez les données de test dans la base de données (facultatif) :
php bin/console doctrine:fixtures:load

6. Lancez le serveur de développement :

symfony server:start


## Utilisation

L'API REST contient trois entités : "category", "items" et "picture". Chacune d'entre elles possède un contrôleur correspondant, qui expose les points de terminaison suivants :

### Endpoints Category
•GET /api/categories : récupère toutes les catégories

•GET /api/categories/{id} : récupère une catégorie par son ID

•POST /api/categories : crée une nouvelle catégorie

•PUT /api/categories/{id} : met à jour une catégorie par son ID

•DELETE /api/categories/{id} : supprime une catégorie par son ID


### Endpoints Items
•GET /api/items : récupère tous les éléments

•GET /api/items/{id} : récupère un élément par son ID

•POST /api/items : crée un nouvel élément

•PUT /api/items/{id} : met à jour un élément par son ID

•DELETE /api/items/{id} : supprime un élément par son ID


### Endpoints Picture
•GET /api/pictures : récupère toutes les images

•GET /api/pictures/{id} : récupère une image par son ID

•POST /api/pictures : crée une nouvelle image

•PUT /api/pictures/{id} : met à jour une image par son ID

•DELETE /api/pictures/{id} : supprime une image par son ID


Chaque Endpoint accepte les requêtes HTTP suivantes : GET, POST, PUT et DELETE. Les données sont transmises au format JSON.


## Auteur
Ce projet a été créé par MEZIOU Chems
