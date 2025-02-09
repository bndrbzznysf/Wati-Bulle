-- Insert into utilisateur
INSERT INTO utilisateur (mail_utilisateur, mdp_utilisateur, nom_utilisateur, prenom_utilisateur, adresse_utilisateur, photo_profil)
VALUES 
('jean.dupont@example.com', 'motdepasse123', 'Dupont', 'Jean', '1 Rue de Paris', NULL),
('marie.martin@example.com', 'motdepasse456', 'Martin', 'Marie', '2 Avenue des Champs', NULL),
('pierre.durand@example.com', 'motdepasse789', 'Durand', 'Pierre', '3 Boulevard de Lyon', NULL),
('sophie.lefebvre@example.com', 'motdepasse101', 'Lefebvre', 'Sophie', '4 Rue de Marseille', NULL),
('luc.moreau@example.com', 'motdepasse202', 'Moreau', 'Luc', '5 Rue de Bordeaux', NULL),
('claire.petit@example.com', 'motdepasse303', 'Petit', 'Claire', '6 Rue de Lille', NULL),
('thomas.roux@example.com', 'motdepasse404', 'Roux', 'Thomas', '7 Rue de Nantes', NULL),
('laura.blanc@example.com', 'motdepasse505', 'Blanc', 'Laura', '8 Rue de Strasbourg', NULL),
('nicolas.girard@example.com', 'motdepasse606', 'Girard', 'Nicolas', '9 Rue de Toulouse', NULL),
('julie.lemoine@example.com', 'motdepasse707', 'Lemoine', 'Julie', '10 Rue de Nice', NULL),
('alice.dubois@example.com', 'password123', 'Dubois', 'Alice', '11 Rue de Lyon', 'alice.jpg'),
('bob.martin@example.com', 'password456', 'Martin', 'Bob', '12 Rue de Marseille', 'bob.jpg');

-- Insert into groupe
INSERT INTO groupe (nom_groupe, description_groupe, couleur, budget_groupe) VALUES
('Club de Tennis', 'Groupe pour les amateurs de tennis', 'bleu', 10000),
('Association des Jardiniers', 'Groupe pour les passionnés de jardinage', 'vert', 5000),
('Conseil de Quartier', 'Groupe pour discuter des améliorations du quartier', 'rouge', 20000),
('Club de Lecture', 'Groupe pour les amateurs de livres', 'jaune', 3000),
('Association des Cyclistes', 'Groupe pour les amateurs de vélo', 'orange', 4000),
('Club de Musique', 'Groupe pour les musiciens amateurs', 'violet', 2500);

-- Insert into theme
INSERT INTO theme (nom_theme) VALUES
('Environnement'),
('Éducation'),
('Santé'),
('Transport'),
('Culture'),
('Économie'),
('Social'),
('Sport'),
('Technologie'),
('Loisirs'),
('Tourisme'),
('Logement');

-- Insert into theme_groupe
INSERT INTO theme_groupe (id_groupe, id_theme, budget_theme_groupe) VALUES 
(1, 8, 3000), -- Club de Tennis -> Sport
(1, 10, 2000), -- Club de Tennis -> Loisirs
(2, 1, 2000), -- Association des Jardiniers -> Environnement
(2, 10, 1000), -- Association des Jardiniers -> Loisirs
(3, 1, 5000), -- Conseil de Quartier -> Environnement
(3, 4, 3000), -- Conseil de Quartier -> Transport
(3, 12, 2000), -- Conseil de Quartier -> Logement
(4, 5, 1000), -- Club de Lecture -> Culture
(4, 10, 500), -- Club de Lecture -> Loisirs
(5, 4, 2000), -- Association des Cyclistes -> Transport
(5, 1, 1000), -- Association des Cyclistes -> Environnement
(5, 10, 500), -- Association des Cyclistes -> Loisirs
(6, 5, 1000), -- Club de Musique -> Culture
(6, 10, 500); -- Club de Musique -> Loisirs

-- Insert into proposition
INSERT INTO proposition (titre, description, date_creation, duree_discussion_min, id_groupe, mail_utilisateur, budget_proposition)
VALUES 
('Nouveau court de tennis', 'Proposer la construction d’un nouveau court de tennis dans le parc', NOW(), 60, 1, 'jean.dupont@example.com', 1500),
('Atelier de jardinage', 'Organiser un atelier pour apprendre à planter des légumes', NOW(), 60, 2, 'marie.martin@example.com', 500),
('Nouveau parc pour enfants', 'Créer un parc pour enfants dans le quartier', NOW(), 60, 3, 'pierre.durand@example.com', 3000),
('Nouveau livre du mois', 'Proposer un nouveau livre pour le club de lecture', NOW(), 60, 4, 'sophie.lefebvre@example.com', 200),
('Piste cyclable sécurisée', 'Demander une piste cyclable sécurisée dans la ville', NOW(), 60, 5, 'luc.moreau@example.com', 2500),
('Concert en plein air', 'Organiser un concert en plein air pour le club de musique', NOW(), 60, 6, 'claire.petit@example.com', 1000),
('Tournoi de tennis', 'Organiser un tournoi de tennis pour le club', NOW(), 60, 1, 'thomas.roux@example.com', 800),
('Atelier compostage', 'Organiser un atelier sur le compostage', NOW(), 60, 2, 'laura.blanc@example.com', 300),
('Nouveau banc dans le parc', 'Installer un nouveau banc dans le parc du quartier', NOW(), 60, 3, 'nicolas.girard@example.com', 400),
('Discussion sur un classique', 'Discuter d’un classique de la littérature', NOW(), 60, 4, 'julie.lemoine@example.com', 100);

-- Insert into theme_proposition
INSERT INTO theme_proposition (id_proposition, id_theme) VALUES
(1, 8), -- Nouveau court de tennis -> Sport
(1, 10), -- Nouveau court de tennis -> Loisirs
(2, 1), -- Atelier de jardinage -> Environnement
(2, 10), -- Atelier de jardinage -> Loisirs
(3, 1), -- Nouveau parc pour enfants -> Environnement
(3, 12), -- Nouveau parc pour enfants -> Logement
(4, 5), -- Nouveau livre du mois -> Culture
(4, 6), -- Nouveau livre du mois -> Économie
(5, 4), -- Piste cyclable sécurisée -> Transport
(5, 1), -- Piste cyclable sécurisée -> Environnement
(6, 5), -- Concert en plein air -> Culture
(6, 10), -- Concert en plein air -> Loisirs
(7, 8), -- Tournoi de tennis -> Sport
(7, 10), -- Tournoi de tennis -> Loisirs
(8, 1), -- Atelier compostage -> Environnement
(8, 10), -- Atelier compostage -> Loisirs
(9, 12), -- Nouveau banc dans le parc -> Logement
(9, 10), -- Nouveau banc dans le parc -> Loisirs
(10, 5); -- Discussion sur un classique -> Culture

-- Insert into commentaire
INSERT INTO commentaire (contenu_commentaire, date_commentaire, id_proposition, mail_utilisateur)
VALUES 
('Super idée ! J’adorerais un nouveau court de tennis.', NOW(), 1, 'marie.martin@example.com'),
('Je suis pour, mais il faut penser au budget.', NOW(), 1, 'pierre.durand@example.com'),
('Je participerai volontiers à cet atelier.', NOW(), 2, 'sophie.lefebvre@example.com'),
('Un parc pour enfants serait génial pour le quartier.', NOW(), 3, 'luc.moreau@example.com'),
('Je propose "1984" de George Orwell.', NOW(), 4, 'claire.petit@example.com'),
('Une piste cyclable serait très utile pour les déplacements.', NOW(), 5, 'thomas.roux@example.com'),
('Je suis musicien, je peux participer au concert.', NOW(), 6, 'laura.blanc@example.com'),
('Je participerai au tournoi de tennis.', NOW(), 7, 'nicolas.girard@example.com'),
('Le compostage est une excellente initiative.', NOW(), 8, 'julie.lemoine@example.com'),
('Un banc supplémentaire serait très apprécié.', NOW(), 9, 'jean.dupont@example.com');

-- Insert into reaction
INSERT INTO reaction (reaction, emoji, est_active) VALUES
('Like', '👍', 0),
('Love', '❤️', 0),
('Haha', '😄', 0),
('Wow', '😲', 0),
('Sad', '😢', 0),
('Angry', '😡', 0),
('Dislike', '👎', 0),
('Celebrate', '🎉', 0),
('Thankful', '🙏', 0);

-- Insert into vote
-- Insert into vote
INSERT INTO vote (date_debut_vote, duree_vote, mail_utilisateur, valeur_vote, id_vote_proposition) VALUES
(NOW(), 1440, 'jean.dupont@example.com', 'oui', 1), 
(NOW(), 1440, 'marie.martin@example.com', 'oui', 1),
(NOW(), 1440, 'pierre.durand@example.com', 'non', 1),
(NOW(), 1440, 'marie.martin@example.com', 'pour', 2),
(NOW(), 1440, 'pierre.durand@example.com', 'pour', 2),
(NOW(), 1440, 'sophie.lefebvre@example.com', 'non', 3),
(NOW(), 1440, 'luc.moreau@example.com', 'oui', 4),
(NOW(), 1440, 'claire.petit@example.com', 'oui', 4),
(NOW(), 1440, 'thomas.roux@example.com', 'pour', 5),
(NOW(), 1440, 'laura.blanc@example.com', 'contre', 5),
(NOW(), 1440, 'nicolas.girard@example.com', 'pour', 5);


-- Insert into vote_proposition
INSERT INTO vote_proposition (id_proposition, type_vote) VALUES
(1, 'oui/non'), 
(2, 'pour/contre'), 
(3, 'oui/non'), 
(4, 'oui/non'), 
(5, 'pour/contre'), 
(6, 'pour/contre');


-- Insert into membre_groupe
INSERT INTO membre_groupe (mail_utilisateur, id_groupe, role) VALUES 
('jean.dupont@example.com', 1, 'Administrateur'),
('marie.martin@example.com', 2, 'Administrateur'),
('pierre.durand@example.com', 3, 'Administrateur'),
('sophie.lefebvre@example.com', 4, 'Administrateur'),
('luc.moreau@example.com', 5, 'Administrateur'),
('claire.petit@example.com', 6, 'Administrateur'),
('thomas.roux@example.com', 1, 'Membre'),
('laura.blanc@example.com', 2, 'Membre'),
('nicolas.girard@example.com', 3, 'Membre'),
('julie.lemoine@example.com', 4, 'Membre');

-- Insert into signalement
INSERT INTO signalement (raison_signalement) VALUES
('Contenu inapproprié'),
('Langage offensant'),
('Spam ou publicité non autorisée'),
('Harcèlement'),
('Informations erronées'),
('Violation des droits d''auteur'),
('Contenu violent'),
('Discours haineux'),
('Contenu à caractère sexuel'),
('Autre raison');

-- Insert into invitation
INSERT INTO invitation (mail_invite, lien, statut, message, id_groupe) VALUES
('jean.dupont@example.com', 'https://example.com/invite1', 'En attente', 'Rejoignez notre groupe de tennis!', 1),
('marie.martin@example.com', 'https://example.com/invite2', 'En attente', 'Rejoignez notre groupe de jardinage!', 2);

-- Insert into notification
INSERT INTO notification (message, id_groupe, mail_utilisateur, lue) VALUES
('Nouvelle proposition soumise: Nouveau court de tennis', 1, 'jean.dupont@example.com', 0),
('Nouveau commentaire sur la proposition: Atelier de jardinage', 2, 'marie.martin@example.com', 0);

-- Insert into reaction_proposition
INSERT INTO reaction_proposition (id_proposition, id_reaction, mail_utilisateur) VALUES
(1, 1, 'marie.martin@example.com'), -- Like sur la proposition 1
(2, 2, 'pierre.durand@example.com'); -- Love sur la proposition 2

-- Insert into reaction_commentaire
INSERT INTO reaction_commentaire (id_commentaire, id_reaction, mail_utilisateur) VALUES
(1, 1, 'jean.dupont@example.com'), -- Like sur le commentaire 1
(2, 2, 'marie.martin@example.com'); -- Love sur le commentaire 2