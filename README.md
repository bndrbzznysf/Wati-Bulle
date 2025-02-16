# Wati-Bulle - Projet de Démocratie Participative

**Ce projet a été réalisé en trinôme, en collaboration avec [@adbernar](https://github.com/adbernar) et [@CRSSukumar](https://github.com/CRSSukumar), un grand merci à eux pour leur contribution !**  

L'objectif est de développer une plateforme de démocratie participative permettant à des groupes (associations, clubs, communes, etc.) de proposer, discuter et voter sur des idées ou des propositions. L'objectif est de fournir un outil open source et facilement utilisable pour organiser des référendums d'initiative citoyenne. La plateforme doit être accessible via une interface web et mobile, avec une base de données pour gérer les utilisateurs, les groupes, les propositions, les commentaires et les votes.

## Fonctionnalités Principales

**1. Création et Gestion de Compte Utilisateur :** Les utilisateurs peuvent créer un compte avec leur nom, prénom, adresse postale et email. Un email de confirmation est envoyé pour activer le compte et les utilisateurs peuvent se connecter et supprimer leur compte.

**2. Création et Gestion de Groupes :** Un utilisateur peut créer un groupe (par exemple, un club de sport) en définissant un nom, une description, une image et une couleur distinctive. L'administrateur du groupe peut inviter d'autres utilisateurs à rejoindre le groupe via un lien d'invitation par email. Les membres peuvent accepter ou refuser l'invitation et rejoindre le groupe.

**3. Propositions et Discussions :** Les membres d'un groupe peuvent soumettre des propositions avec un titre, une description et une étiquette/thème. Les autres membres peuvent commenter les propositions et réagir avec des émoticônes (j'aime, j'aime pas, etc.). Les commentaires inappropriés peuvent être signalés et supprimés par les modérateurs ou l'administrateur.

**4. Votes :** Après une phase de discussion, les membres peuvent demander un vote formel sur une proposition. L'administrateur configure le scrutin (Oui/Non, Pour/Contre, etc.) et lance le vote. Les votes sont anonymes mais traçables, et les résultats sont validés par un scrutateur.

**5. Rôle des Décideurs :** Les décideurs (maire, président d'association, etc.) peuvent évaluer les propositions en termes de budget. Une application spécifique aide les décideurs à choisir les propositions les plus populaires tout en respectant les contraintes budgétaires.

## Structure du Projet

Le projet est divisé en trois parties principales :

### 1. Partie Web
- Développement de l'interface utilisateur (web et mobile) avec une charte graphique responsive.
- Implémentation des services REST en PHP pour gérer les requêtes entre le frontend et la base de données.

### 2. Partie Base de Données
- Conception et implémentation de la base de données en MySQL.
- Création de scripts SQL pour la création des tables et l'insertion des données de test.
- Implémentation de triggers et de fonctions PL/SQL pour automatiser certaines tâches.

### 3. Partie Application Java
- Développement d'une application Java pour les décideurs, permettant de simuler des stratégies budgétaires et de valider les propositions.
