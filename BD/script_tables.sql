USE `saes3-ibendar`;

CREATE TABLE utilisateur(
   mail_utilisateur VARCHAR(100),
   mdp_utilisateur VARCHAR(255) NOT NULL,
   nom_utilisateur VARCHAR(100) NOT NULL,
   prenom_utilisateur VARCHAR(100) NOT NULL,
   adresse_utilisateur VARCHAR(100) NOT NULL,
   photo_profil VARCHAR(255) DEFAULT NULL,
   frequence_notification ENUM('Quotidienne', 'Hebdomadaire') DEFAULT 'Quotidienne',
   PRIMARY KEY(mail_utilisateur)
);


CREATE TABLE groupe(
   id_groupe INT AUTO_INCREMENT,
   nom_groupe VARCHAR(100) NOT NULL,
   description_groupe VARCHAR(300),
   image VARCHAR(200) DEFAULT NULL,
   couleur VARCHAR(50) NOT NULL,
   budget_groupe INT DEFAULT NULL,
   PRIMARY KEY(id_groupe),
   UNIQUE(nom_groupe)
);


CREATE TABLE membre_groupe(
   mail_utilisateur VARCHAR(100),
   id_groupe INT,
   role ENUM('Administrateur', 'Modérateur', 'Scrutateur', 'Assesseur', 'Décideur', 'Membre') DEFAULT 'Membre',
   PRIMARY KEY(mail_utilisateur, id_groupe),
   FOREIGN KEY(mail_utilisateur) REFERENCES utilisateur(mail_utilisateur),
   FOREIGN KEY(id_groupe) REFERENCES groupe(id_groupe)
);


CREATE TABLE proposition(
   id_proposition INT AUTO_INCREMENT,
   titre VARCHAR(100) NOT NULL,
   description VARCHAR(300) NOT NULL,
   date_creation DATETIME DEFAULT CURRENT_TIMESTAMP, 
   duree_discussion_min INT NOT NULL,
   id_groupe INT NOT NULL,
   mail_utilisateur VARCHAR(100),
   budget_proposition INT,
   satisfaction DECIMAL(5, 2) DEFAULT NULL,
   decision ENUM('Acceptée', 'Refusée', 'En attente') DEFAULT 'En attente',
   PRIMARY KEY(id_proposition),
   FOREIGN KEY(id_groupe) REFERENCES groupe(id_groupe),
   FOREIGN KEY(mail_utilisateur) REFERENCES utilisateur(mail_utilisateur)
);


CREATE TABLE theme(
   id_theme INT AUTO_INCREMENT,
   nom_theme VARCHAR(50) NOT NULL,
   PRIMARY KEY(id_theme)
);


CREATE TABLE commentaire(
   id_commentaire INT AUTO_INCREMENT,
   contenu_commentaire VARCHAR(300) NOT NULL,
   date_commentaire DATETIME NOT NULL,
   id_proposition INT,
   mail_utilisateur VARCHAR(100),
   PRIMARY KEY(id_commentaire),
   FOREIGN KEY(id_proposition) REFERENCES proposition(id_proposition),
   FOREIGN KEY(mail_utilisateur) REFERENCES utilisateur(mail_utilisateur)
);


-- La durée du vote est en minutes
CREATE TABLE vote_proposition(
   id_vote_proposition INT AUTO_INCREMENT,
   id_proposition INT,
   resultat_vote VARCHAR(50) DEFAULT NULL,
   type_vote VARCHAR(50) NOT NULL,
   PRIMARY KEY(id_vote_proposition),
   FOREIGN KEY(id_proposition) REFERENCES proposition(id_proposition)
);


CREATE TABLE vote(
   id_vote INT AUTO_INCREMENT,
   id_vote_proposition INT,
   date_debut_vote DATETIME NOT NULL,
   duree_vote INT NOT NULL, 
   mail_utilisateur VARCHAR(100) NOT NULL, 
   valeur_vote VARCHAR(50) NOT NULL,
   PRIMARY KEY(id_vote),   
   FOREIGN KEY(mail_utilisateur) REFERENCES utilisateur(mail_utilisateur),
   FOREIGN KEY(id_vote_proposition) REFERENCES vote_proposition(id_vote_proposition)
);


CREATE TABLE signalement(
   id_signalement INT AUTO_INCREMENT,
   raison_signalement VARCHAR(50) NOT NULL,
   PRIMARY KEY(id_signalement)
);


CREATE TABLE reaction(
   id_reaction INT AUTO_INCREMENT,
   reaction VARCHAR(50) NOT NULL,
   emoji VARCHAR(10),
   est_active TINYINT(1) DEFAULT 0,
   PRIMARY KEY(id_reaction)
);


CREATE TABLE notification(
   id_notification INT AUTO_INCREMENT,
   message VARCHAR(300) NOT NULL,
   id_groupe INT NOT NULL,
   mail_utilisateur VARCHAR(100) NOT NULL,
   lue TINYINT(1) DEFAULT 0,
   PRIMARY KEY(id_notification),
   FOREIGN KEY(id_groupe) REFERENCES groupe(id_groupe),
   FOREIGN KEY(mail_utilisateur) REFERENCES utilisateur
);


CREATE TABLE invitation(
   id_invitation INT AUTO_INCREMENT,
   mail_invite VARCHAR(100) NOT NULL,
   role ENUM('Administrateur', 'Modérateur', 'Scrutateur', 'Assesseur', 'Décideur', 'Membre') DEFAULT 'Membre',
   lien VARCHAR(200) UNIQUE NOT NULL,
   statut ENUM ('En attente', 'Acceptée') DEFAULT 'En attente',
   message VARCHAR(300) NOT NULL,
   id_groupe INT NOT NULL,
   PRIMARY KEY(id_invitation),
   FOREIGN KEY(id_groupe) REFERENCES groupe(id_groupe)
);


CREATE TABLE theme_groupe(
   id_groupe INT,
   id_theme INT,
   budget_theme_groupe INT,
   PRIMARY KEY(id_groupe, id_theme),
   FOREIGN KEY(id_groupe) REFERENCES groupe(id_groupe),
   FOREIGN KEY(id_theme) REFERENCES theme(id_theme)
);


CREATE TABLE theme_proposition(
   id_proposition INT,
   id_theme INT,
   PRIMARY KEY(id_proposition, id_theme),
   FOREIGN KEY(id_proposition) REFERENCES proposition(id_proposition),
   FOREIGN KEY(id_theme) REFERENCES theme(id_theme)
);


CREATE TABLE reaction_commentaire(
   id_commentaire INT,
   id_reaction INT,
   mail_utilisateur VARCHAR(100),
   PRIMARY KEY(id_commentaire, id_reaction),
   FOREIGN KEY(id_commentaire) REFERENCES commentaire(id_commentaire),
   FOREIGN KEY(id_reaction) REFERENCES reaction(id_reaction)
);


CREATE TABLE reaction_proposition(
   id_proposition INT,
   id_reaction INT,
   mail_utilisateur VARCHAR(100),
   PRIMARY KEY(id_proposition, id_reaction),
   FOREIGN KEY(id_proposition) REFERENCES proposition(id_proposition),
   FOREIGN KEY(id_reaction) REFERENCES reaction(id_reaction)
);


CREATE TABLE signalement_commentaire(
   id_commentaire INT,
   id_signalement INT,
   mail_utilisateur VARCHAR(100),
   PRIMARY KEY(id_commentaire, id_signalement),
   FOREIGN KEY(id_commentaire) REFERENCES commentaire(id_commentaire),
   FOREIGN KEY(id_signalement) REFERENCES signalement(id_signalement),
   FOREIGN KEY(mail_utilisateur) REFERENCES utilisateur(mail_utilisateur)
);


CREATE TABLE signalement_proposition(
   id_proposition INT,
   id_signalement INT,
   mail_utilisateur VARCHAR(100),
   PRIMARY KEY(id_proposition, id_signalement),
   FOREIGN KEY(id_proposition) REFERENCES proposition(id_proposition),
   FOREIGN KEY(id_signalement) REFERENCES signalement(id_signalement),
   FOREIGN KEY(mail_utilisateur) REFERENCES utilisateur(mail_utilisateur)
);
