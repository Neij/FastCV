# FastCV - Site de Création de CV

/*******************************************************/
-------Bienvenue sur le site de création de CV.----------
/*******************************************************/

Ce projet vise à fournir une plateforme conviviale pour permettre aux utilisateurs de créer et gérer leurs CV rapidement et facielement en ligne.

Fonctionnalités Principales :
Création de CV : Les utilisateurs peuvent créer et éditer leurs CV en utilisant une interface simple et intuitive.
Gestion des Utilisateurs : L'administrateur peut gérer les utilisateurs du site.

/*******************************************************/
----------------Installation de FastCV-------------------
/*******************************************************/

Pour installer et exécuter FastCV localement, suivez ces étapes :

Prérequis
WampServer installé sur votre machine.

Instructions
Téléchargement : Clonez ce dépôt GitHub dans le répertoire www de votre installation WampServer.

git clone https://github.com/Neij/FastCV.git 

/*******************************************************/
-------------Migrations de la Base de Données-------------
/*******************************************************/

Exécution des Migrations de la Base de Données
FastCV utilise des migrations pour configurer la structure de la base de données. Suivez ces étapes pour exécuter les migrations SQL nécessaires dans votre base de données MySQL :

À partir de l'Interface MySQL
Connexion à MySQL : Connectez-vous à votre serveur MySQL en utilisant l'interface graphique de votre choix.

Sélection de la Base de Données : Sélectionnez la base de données que vous avez spécifiée dans le fichier config.php.

Exécution des Fichiers de Migration : Ouvrez chaque fichier de migration situé dans le répertoire migrations avec un éditeur de texte ou un éditeur SQL. Copiez le contenu du fichier et exécutez-le dans l'interface MySQL.

Répétez ces étapes pour tous les fichiers de migration nécessaires.

/*******************************************************/
----------------Configuration config.php----------------
/*******************************************************/

Configuration : Modifiez le fichier config.php dans le répertoire config avec vos informations de connexion à la base de données.

Assurez-vous de remplacer 'nom_de_la_base_de_donnees', 'votre_nom_utilisateur', et 'votre_mot_de_passe' par vos propres informations.



Avec ces étapes, vous devriez avoir FastCV opérationnel sur votre environnement local.

Voici un exemple de configuration :

<?php

if (!defined('DB_HOST')) {
    define("DB_HOST", 'localhost');
    define("DB_NAME", 'julienlegoff_fastcv');
    define("DB_USER", 'root');
    define("DB_PASS", '');
}
/*******************************************************/
-----------Création d'un Compte Utilisateur------------
/*******************************************************/

Accéder au Site : Lancez votre serveur Wamp et accédez au site à l'aide de votre navigateur en visitant http://localhost/nom_du_dossier.

Pour explorer les fonctionnalités de FastCV, vous pouvez créer un compte utilisateur en suivant ces étapes :

Accédez à la Page d'Inscription :

Utilisez votre navigateur pour visiter http://localhost/nom_du_dossier.
Cliquez sur le lien d'inscription pour accéder à la page de création de compte.
Remplissez le Formulaire d'Inscription :

Entrez un nom d'utilisateur de votre choix.
Choisissez un mot de passe sécurisé.
Fournissez une adresse e-mail valide.
Validez l'Inscription :

Soumettez le formulaire pour créer votre compte utilisateur.
Connexion au Site :

Une fois inscrit, retournez à la page principale et utilisez vos identifiants pour vous connecter.

/*******************************************************/
-----------Instructions pour l'Administrateur------------
/*******************************************************/

Accéder à l'interface d'administration :

Pour bénéficier d'un accès à l'interface d'administration, suivez ces étapes :

1. **Création du Compte Administrateur :**
   - Assurez-vous d'être connecté en tant qu'utilisateur.
   - Créez un nouveau compte en tant qu'administrateur avec les informations suivantes :
     - Nom d'utilisateur : admin
     - Mot de passe : Admin29@

2. **Connexion en tant qu'Administrateur :**
   - Accédez à la page de connexion.
   - Utilisez les identifiants suivants :
     - Nom d'utilisateur : admin
     - Mot de passe : Admin29@

Suppression d'un Utilisateur :

1. Accédez à la page d'administration en vous connectant avec les identifiants d'administrateur.
2. Une fois connecté, vous verrez une liste d'utilisateurs enregistrés.
3. Pour supprimer un utilisateur, cliquez sur le bouton correspondant à l'utilisateur que vous souhaitez supprimer.
4. La suppression sera confirmée, et l'utilisateur ainsi que toutes ses informations seront retirés de la base de données.

/*******************************************************/
-----------Instructions pour l'Administrateur------------
/*******************************************************/

Modèles (Models) :
Les modèles gèrent l'accès à la base de données et contiennent les requêtes SQL nécessaires pour manipuler les données. Voici quelques-uns des modèles inclus dans le projet :

Users : Gère les opérations liées aux utilisateurs, telles que la création, la mise à jour, et la suppression.
Database : Gère la connexion à la base de données.
UsersAdmin : Modèle spécifique pour la gestion des utilisateurs administrateurs.
Contrôleurs (Controllers) :
Les contrôleurs orchestrent le flux de données entre les modèles et les vues. Ils contiennent la logique de traitement des requêtes. Dans ce projet, nous avons des contrôleurs tels que :

LoginController : Gère le processus d'authentification des utilisateurs.
AdminController : Gère l'interface d'administration et les opérations liées aux utilisateurs.

Vues (Views) :
Les vues sont responsables de l'affichage des données. Chaque contrôleur peut avoir sa propre vue associée. Par exemple, login.phtml pour la page de connexion et admin.phtml pour l'interface d'administration.

/*******************************************************/
---------------Technologies Utilisées-------------------
/*******************************************************/

HTML, CSS (Sass), JavaScript : La structure, la présentation, et le comportement du site.

PHP : Langage serveur pour la logique métier et l'interaction avec la base de données.

MySQL : Système de gestion de base de données relationnelle.

/*******************************************************/
----------------------Fichier Index---------------------
/*******************************************************/

Autoload et Autoloading des Classes

Le fichier index.php utilise la fonction spl_autoload_register pour charger automatiquement les classes nécessaires au fur et à mesure de leur utilisation. Cela simplifie la gestion des dépendances.

Routes et Contrôleurs

Les différentes routes sont gérées dans une structure de commutation (switch) en fonction des paramètres de requête (route). Chaque route correspond à un contrôleur spécifique, qui est instancié et exécute les actions appropriées.

Par exemple :
La route 'home' est associée au HomeController.
La route 'login' est associée au LoginController.

Déconnexion

La route 'logout' est spécialement dédiée à la déconnexion. Elle détruit la session utilisateur et redirige vers la page d'accueil.

Gestion des Erreurs

Les exceptions sont capturées et affichent un message d'erreur en cas de problème. Ceci assure une meilleure gestion des erreurs pour le débogage.

Autres Routes

Il existe d'autres routes pour des fonctionnalités spécifiques telles que la création, la modification, et la suppression d'éléments du CV, l'accès à l'interface d'administration, etc.

Page par Défaut

Si aucune route n'est spécifiée, l'utilisateur est redirigé vers la page d'accueil par défaut.


/*******************************************************/
-----------------------Conclusion-----------------------
/*******************************************************/

FastCV est un projet de création de CV en ligne, offrant une plateforme conviviale pour les utilisateurs souhaitant gérer et créer leurs CV de manière rapide et efficace. 
