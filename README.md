# Todo App V1
Une application web pour la gestion de liste des tâches, sous php qui regroupe les fonctionnalité d'un CRUD.

## Technologies Utilisées

- PHP 8
- MySQL
- PDO
- HTML/CSS

## Prérequis

Avant de commencer, assurez-vous d'avoir installé les éléments suivants :

- [PHP 8+](https://www.php.net/downloads)
- [MySQL](https://dev.mysql.com/downloads/)
- Un serveur web tel que [Apache](https://httpd.apache.org/download.cgi) ou [Nginx](https://nginx.org/en/download.html)

## Installation

1. Clonez le dépôt :
```sh
   git clone https://github.com/Tarik-B-dev/to-do--app
   cd todo-app
```


## Création de la Base de Données

```sh
   CREATE DATABASE todo_app;
   USE todo_app;
```

## Création de la Table User

```sh
   CREATE TABLE users ( 
   user_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
   date_de_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
   pseudo varchar(255),
   email varchar(255) NOT NULL,
   password varchar(255) NOT NULL
   ) ENGINE=InnoDB DEFAULT CHARSET=UTF8MB4;
```

## Création de la Table Tasks

```sh
   CREATE TABLE tasks (
   task_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
   Date_De_Modification DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
   title VARCHAR(255),
   user_id INT,
   CONSTRAINT FOREIGN KEY(user_id) REFERENCES users(user_id)
   ON DELETE CASCADE ON UPDATE CASCADE
   ) ENGINE=InnoDB DEFAULT CHARSET=UTF8MB4;
```


## IMPORTANT
Ne pas oublier de changer les informations de connexion à la base de données dans le fichier "/libs/config/pdo.php", sachez que les informations sensibles doivent resté confidentiels et ne doivent pas appraître sur votre dépôt gitHub public.


### Mise en route :
La mise en route avec MAMP (MacOS) OU XAMPP/WAMP (Windows) est simple, si non Docker est une solution populaire et multiplateforme pour créer des environnements de développement isolés, indépendamment du système d'exploitation.(D'ailleurs Docker sera choisie pour la V2 de l'app)

- Si vous choisissez Docker il vous faut modifier le fichier "/libs/config/pdo.php" afin de changer le $host --> "mysql" au lieu de "localhost" , et rajouter 2 fichiers supplémentaires : 
   + compose.yml
   + Dockerfile



#### Je suis ravi de partager ça avec vous, et j'autorise toute forme d'utilisation de ce travail, tant que c'est conforme à la loi.