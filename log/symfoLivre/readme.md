## rudylopez8
symfoLivres
Ce projet est une bibliotèque simple réalisé avec Symfony. Il permet aux utilisateurs de lire des livres, aux auteurs de faire de même et de gérer leurs livres, et aux administrateurs de gérer l'intégralité du site.

Prérequis
• PHP 8.0+
• Composer
• SGBD (type WAMP ou LAMP serveur).
• MySQL 5 & 8


Installation
1. Clonez ou téléchargez le projet sur votre ordinateur
pour cloner git clone "url du projet"

2. Installez les dépendances avec Composer :
composer install
si il y a un problème on peut forcer avec composer update --lock

3. Créez une base de données MySQL et configurez les paramètres de connexion dans le fichier .env
php bin/console doctrine:database:create
php bin/console make migration
php bin/console doctrine:migration:migrate

4. Lancez le serveur web :
symfony server:start -d
(pour le stoper symfony server:stop)
Le projet sera accessible à l'adresse indiquer par le serveur symfony à son lancemant généralement http://127.0.0.1:8000

Ce projet est sous licence GNU ( GPL ) Version 3, 29 06 2007.
consultable à https://www.gnu.org/licenses/gpl-3.0.html
