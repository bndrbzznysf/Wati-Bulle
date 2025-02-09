DELIMITER $$
CREATE FUNCTION `calculer_budget_restant_groupe`(p_id_groupe INT) RETURNS int(11)
    READS SQL DATA
BEGIN
    DECLARE total_budget INT;
    DECLARE used_budget INT;

    SELECT budget_groupe INTO total_budget
    FROM groupe
    WHERE id_groupe = p_id_groupe;
    SET used_budget = calculer_budget_utilise_groupe(p_id_groupe);
    RETURN total_budget - used_budget;
END$$
DELIMITER ;


DELIMITER $$
CREATE FUNCTION `calculer_budget_restant_theme_groupe`(p_id_groupe INT, p_id_theme INT) RETURNS int(11)
    READS SQL DATA
BEGIN
    DECLARE allocated_budget INT;
    DECLARE used_budget INT;
    
    SELECT budget_theme_groupe INTO allocated_budget
    FROM theme_groupe
    WHERE id_groupe = p_id_groupe
    AND id_theme = p_id_theme;
    SET used_budget = calculer_budget_utilise_theme_groupe(p_id_groupe, p_id_theme);
    RETURN allocated_budget - used_budget;
END$$
DELIMITER ;


DELIMITER $$
CREATE FUNCTION `calculer_budget_utilise_groupe`(p_id_groupe INT) RETURNS int(11)
    READS SQL DATA
BEGIN
    DECLARE total_budget INT;
    SELECT COALESCE(SUM(budget_proposition), 0) INTO total_budget
    FROM proposition
    WHERE id_groupe = p_id_groupe;
    RETURN total_budget;
END$$
DELIMITER ;


DELIMITER $$
CREATE FUNCTION `calculer_budget_utilise_theme_groupe`(p_id_groupe INT, p_id_theme INT) RETURNS int(11)
    READS SQL DATA
BEGIN
    DECLARE total_budget INT;
    SELECT COALESCE(SUM(p.budget_proposition), 0) INTO total_budget
    FROM proposition p
    JOIN theme_proposition tp ON p.id_proposition = tp.id_proposition
    WHERE p.id_groupe = p_id_groupe
    AND tp.id_theme = p_id_theme;
    RETURN total_budget;
END$$
DELIMITER ;


DELIMITER $$
CREATE FUNCTION `calculer_pourcentage_votes_favorables`(`p_id_proposition` INT) RETURNS decimal(5,2)
    READS SQL DATA
BEGIN
    DECLARE total_votes INT;
    DECLARE favorable_votes INT;
    DECLARE percentage DECIMAL(5, 2);
    
    SELECT COUNT(*) INTO total_votes
    FROM vote
    NATURAL JOIN vote_proposition
    WHERE id_proposition = p_id_proposition;
    
    SELECT COUNT(*) INTO favorable_votes
    FROM vote v
    NATURAL JOIN vote_proposition
    WHERE id_proposition = p_id_proposition
    AND valeur_vote IN ('Oui', 'Pour');
    
    IF total_votes > 0 THEN SET percentage = (favorable_votes / total_votes) * 100;
    ELSE SET percentage = 0;
    END IF;

    RETURN percentage;
END$$
DELIMITER ;


DELIMITER $$
CREATE FUNCTION `compter_membres_groupe`(p_id_groupe INT) RETURNS int(11)
    READS SQL DATA
BEGIN
    DECLARE member_count INT;
    SELECT COUNT(*) INTO member_count
    FROM membre_groupe
    WHERE id_groupe = p_id_groupe;
    RETURN member_count;
END$$
DELIMITER ;


DELIMITER $$
CREATE FUNCTION `compter_propositions_acceptees_groupe`(p_id_groupe INT) RETURNS int(11)
    READS SQL DATA
BEGIN
    DECLARE accepted_count INT;
    SELECT COUNT(*) INTO accepted_count
    FROM proposition
    WHERE id_groupe = p_id_groupe
    AND decision = 'Acceptée';
    RETURN accepted_count;
END$$
DELIMITER ;


DELIMITER $$
CREATE FUNCTION `compter_propositions_en_attente_groupe`(p_id_groupe INT) RETURNS int(11)
    READS SQL DATA
BEGIN
    DECLARE pending_count INT;
    SELECT COUNT(*) INTO pending_count
    FROM proposition
    WHERE id_groupe = p_id_groupe
    AND decision = 'En attente';
    RETURN pending_count;
END$$
DELIMITER ;


DELIMITER $$
CREATE FUNCTION `compter_propositions_groupe`(p_id_groupe INT) RETURNS int(11)
    READS SQL DATA
BEGIN
    DECLARE proposal_count INT;
    SELECT COUNT(*) INTO proposal_count
    FROM proposition
    WHERE id_groupe = p_id_groupe;
    RETURN proposal_count;
END$$
DELIMITER ;


DELIMITER $$
CREATE FUNCTION `compter_propositions_rejetees_groupe`(p_id_groupe INT) RETURNS int(11)
    READS SQL DATA
BEGIN
    DECLARE rejected_count INT;
    SELECT COUNT(*) INTO rejected_count
    FROM proposition
    WHERE id_groupe = p_id_groupe
    AND decision = 'Refusée';
    RETURN rejected_count;
END$$
DELIMITER ;


DELIMITER $$
CREATE FUNCTION `compter_vote_utilisateur`(p_vote_utilisateur VARCHAR(50)) RETURNS int(11)
    READS SQL DATA
BEGIN
    DECLARE total_votes INT;
    SELECT COUNT(*) INTO total_votes
    FROM vote v
    WHERE vote_utilisateur = p_vote_utilisateur;
    RETURN total_votes;
END$$
DELIMITER ;


DELIMITER $$
CREATE FUNCTION `compter_votes_proposition`(p_id_proposition INT) RETURNS int(11)
    READS SQL DATA
BEGIN
    DECLARE vote_count INT;
    SELECT COUNT(*) INTO vote_count
    FROM vote_proposition
    WHERE id_proposition = p_id_proposition;
    RETURN vote_count;
END$$
DELIMITER ;


DELIMITER $$
CREATE FUNCTION `determiner_resultat_vote`(IN p_id_proposition INT,
    IN type_vote_pour VARCHAR(10),
    IN type_vote_contre VARCHAR(10)) RETURNS varchar(10) CHARSET utf8mb4 COLLATE utf8mb4_general_ci
    READS SQL DATA
BEGIN
    DECLARE total_pour_votes INT;
    DECLARE total_contre_votes INT;
    DECLARE result VARCHAR(10);

    SELECT COUNT(*) INTO total_pour_votes
    FROM vote_proposition vp
    JOIN vote v ON vp.id_vote = v.id_vote
    WHERE vp.id_proposition = p_id_proposition
    AND v.vote_utilisateur = type_vote_pour;

    SELECT COUNT(*) INTO total_contre_votes
    FROM vote_proposition vp
    JOIN vote v ON vp.id_vote = v.id_vote
    WHERE vp.id_proposition = p_id_proposition
    AND v.vote_utilisateur = type_vote_contre;

    IF total_pour_votes > total_contre_votes THEN
        SET result = type_vote_pour;
    ELSEIF total_contre_votes > total_pour_votes THEN
        SET result = type_vote_contre;
    ELSE
        SET result = 'égalité';
    END IF;

    RETURN result;
END$$
DELIMITER ;


DELIMITER $$
CREATE FUNCTION `est_administrateur_groupe`(p_mail_utilisateur VARCHAR(100), p_id_groupe INT) RETURNS tinyint(1)
    READS SQL DATA
BEGIN
    DECLARE is_admin BOOLEAN;
    SELECT EXISTS(
        SELECT 1 
        FROM membre_groupe 
        WHERE mail_utilisateur = p_mail_utilisateur 
        AND id_groupe = p_id_groupe 
        AND role = 'Administrateur'
    ) INTO is_admin;
    RETURN is_admin;
END$$
DELIMITER ;


DELIMITER $$
CREATE FUNCTION `est_membre_groupe`(p_mail_utilisateur VARCHAR(100), p_id_groupe INT) RETURNS tinyint(1)
    READS SQL DATA
BEGIN
    DECLARE is_member BOOLEAN;
    SELECT EXISTS(
        SELECT 1 
        FROM membre_groupe 
        WHERE mail_utilisateur = p_mail_utilisateur 
        AND id_groupe = p_id_groupe
    ) INTO is_member;
    RETURN is_member;
END$$
DELIMITER ;


DELIMITER $$
CREATE FUNCTION `proposition_est_acceptee`(p_id_proposition INT) RETURNS tinyint(1)
    READS SQL DATA
BEGIN
    DECLARE is_accepted BOOLEAN;
    SELECT decision = 'Acceptée' INTO is_accepted
    FROM proposition
    WHERE id_proposition = p_id_proposition;
    RETURN is_accepted;
END$$
DELIMITER ;


DELIMITER $$
CREATE FUNCTION `utilisateur_a_vote`(p_mail_utilisateur VARCHAR(100), p_id_proposition INT) RETURNS tinyint(1)
    READS SQL DATA
BEGIN
    DECLARE has_voted BOOLEAN;
    SELECT EXISTS(
        SELECT 1 
        FROM vote_proposition vp
        JOIN vote v ON vp.id_vote = v.id_vote
        WHERE vp.id_proposition = p_id_proposition
        AND v.mail_utilisateur = p_mail_utilisateur
    ) INTO has_voted;
    RETURN has_voted;
END$$
DELIMITER ;


DELIMITER $$
CREATE FUNCTION `vote_a_atteint_fin`(p_id_vote INT) RETURNS tinyint(1)
    READS SQL DATA
BEGIN
    DECLARE vote_ended BOOLEAN;
    DECLARE date_fin_vote DATETIME;


    SELECT DATE_ADD(date_debut_vote, INTERVAL duree_vote MINUTE) INTO date_fin_vote
    FROM vote
    WHERE id_vote = p_id_vote;

    SELECT CURRENT_TIMESTAMP >= date_fin_vote INTO vote_ended;

    RETURN vote_ended;
END$$
DELIMITER ;




DELIMITER $$
CREATE PROCEDURE `ajouter_commentaire`(
    IN p_contenu_commentaire VARCHAR(300),
    IN p_id_proposition INT,
    IN p_mail_utilisateur VARCHAR(100),
    OUT p_success BOOLEAN
)
BEGIN
    DECLARE proposition_exists BOOLEAN;
    DECLARE user_exists BOOLEAN;

    SELECT EXISTS(SELECT 1 FROM proposition WHERE id_proposition = p_id_proposition) INTO proposition_exists;
    SELECT EXISTS(SELECT 1 FROM utilisateur WHERE mail_utilisateur = p_mail_utilisateur) INTO user_exists;

    IF proposition_exists AND user_exists THEN
        INSERT INTO commentaire (contenu_commentaire, date_commentaire, id_proposition, mail_utilisateur)
        VALUES (p_contenu_commentaire, NOW(), p_id_proposition, p_mail_utilisateur);
        SET p_success = TRUE;

    ELSE SET p_success = FALSE; 
    END IF;
END$$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE `ajouter_membre_groupe`(
    IN p_mail_utilisateur VARCHAR(100),
    IN p_id_groupe INT,
    IN p_role VARCHAR(50),
    OUT p_success BOOLEAN
)
BEGIN
    DECLARE user_exists BOOLEAN;
    DECLARE group_exists BOOLEAN;
    DECLARE already_member BOOLEAN;
    SELECT EXISTS(SELECT 1 FROM utilisateur WHERE mail_utilisateur = p_mail_utilisateur) INTO user_exists;
    SELECT EXISTS(SELECT 1 FROM groupe WHERE id_groupe = p_id_groupe) INTO group_exists;
    SELECT EXISTS(SELECT 1 FROM membre_groupe WHERE mail_utilisateur = p_mail_utilisateur AND id_groupe = p_id_groupe) INTO already_member;

    IF user_exists AND group_exists AND NOT already_member THEN
        INSERT INTO membre_groupe (mail_utilisateur, id_groupe, role)
        VALUES (p_mail_utilisateur, p_id_groupe, p_role);
        SET p_success = TRUE;

    ELSE SET p_success = FALSE;
    END IF;
END$$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE `ajouter_satisfaction`( IN p_id_proposition INT)
BEGIN
    UPDATE proposition
    SET satisfaction = calculer_pourcentage_votes_favorables(p_id_proposition) 
    WHERE id_proposition = p_id_proposition;
END$$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE `ajouter_theme_a_groupe`(
    IN p_id_groupe INT,
    IN p_id_theme INT,
    IN p_budget_theme_groupe INT,
    OUT p_success BOOLEAN
)
BEGIN
    DECLARE group_exists BOOLEAN;
    DECLARE theme_exists BOOLEAN;
    DECLARE theme_already_linked BOOLEAN;
    SELECT EXISTS(SELECT 1 FROM groupe WHERE id_groupe = p_id_groupe) INTO group_exists;
    SELECT EXISTS(SELECT 1 FROM theme WHERE id_theme = p_id_theme) INTO theme_exists;
    SELECT EXISTS(SELECT 1 FROM theme_groupe WHERE id_groupe = p_id_groupe AND id_theme = p_id_theme) INTO theme_already_linked;

    IF group_exists AND theme_exists AND NOT theme_already_linked THEN
        INSERT INTO theme_groupe (id_groupe, id_theme, budget_theme_groupe)
        VALUES (p_id_groupe, p_id_theme, p_budget_theme_groupe);
        SET p_success = TRUE;

    ELSE SET p_success = FALSE;
    END IF;
END$$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE `ajouter_theme_a_proposition`(
    IN p_id_proposition INT,
    IN p_id_theme INT,
    OUT p_success BOOLEAN
)
BEGIN
    DECLARE proposition_exists BOOLEAN;
    DECLARE theme_exists BOOLEAN;
    SELECT EXISTS(SELECT 1 FROM proposition WHERE id_proposition = p_id_proposition) INTO proposition_exists;
    SELECT EXISTS(SELECT 1 FROM theme WHERE id_theme = p_id_theme) INTO theme_exists;

    IF proposition_exists AND theme_exists THEN
        INSERT INTO theme_proposition (id_proposition, id_theme)
        VALUES (p_id_proposition, p_id_theme);
        SET p_success = TRUE;

    ELSE SET p_success = FALSE;
    END IF;
END$$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE `ajouter_vote`(
    IN p_duree INT,
    IN p_type VARCHAR(50),  
    IN p_mail VARCHAR(100), 
    IN p_vote VARCHAR(50),   
    IN p_id_proposition INT, 
    OUT p_success BOOLEAN 
)
BEGIN
    DECLARE user_exists BOOLEAN;
    DECLARE proposition_exists BOOLEAN;
    DECLARE new_vote_id INT;

    SELECT EXISTS(SELECT 1 FROM utilisateur WHERE mail_utilisateur = p_mail) INTO user_exists;
    SELECT EXISTS(SELECT 1 FROM proposition WHERE id_proposition = p_id_proposition) INTO proposition_exists;

    IF user_exists AND proposition_exists THEN
        INSERT INTO vote (date_debut_vote, duree_vote, type_vote, mail_utilisateur, vote_utilisateur)
        VALUES (CURRENT_TIMESTAMP, p_duree, p_type, p_mail, p_vote);
        SET new_vote_id = LAST_INSERT_ID();
        INSERT INTO vote_proposition (id_proposition, id_vote)
        VALUES (p_id_proposition, new_vote_id);
        SET p_success = TRUE;
        
    ELSE SET p_success = FALSE; END IF;
END$$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE `creer_compte_utilisateur`(
    IN p_mail VARCHAR(100),
    IN p_mdp VARCHAR(255),
    IN p_nom VARCHAR(100),
    IN p_prenom VARCHAR(100),
    IN p_adresse VARCHAR(100),
    IN p_photo_profil VARCHAR(255),
    IN p_frequence_notification ENUM('Quotidienne', 'Hebdomadaire'),
    OUT p_success BOOLEAN
)
BEGIN
    DECLARE user_exists BOOLEAN;
    SELECT EXISTS(SELECT 1 FROM utilisateur WHERE mail_utilisateur = p_mail) INTO user_exists;

    IF user_exists THEN
        SET p_success = FALSE;

    ELSE
        INSERT INTO utilisateur (mail_utilisateur, mdp_utilisateur, nom_utilisateur, prenom_utilisateur, adresse_utilisateur, photo_profil, frequence_notification)
        VALUES (p_mail, p_mdp, p_nom, p_prenom, p_adresse, p_photo_profil, p_frequence_notification);

        SET p_success = TRUE;
    END IF;
END$$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE `creer_groupe`(
    IN p_nom VARCHAR(100),
    IN p_description VARCHAR(300),
    IN p_image VARCHAR(200),
    IN p_couleur VARCHAR(50),
    IN p_mail_administrateur VARCHAR(100),
    IN p_budget_groupe INT,
    OUT p_success BOOLEAN
)
BEGIN
    DECLARE group_exists BOOLEAN;
    SELECT EXISTS(SELECT 1 FROM groupe WHERE nom_groupe = p_nom) INTO group_exists;

    IF group_exists THEN SET p_success = FALSE; 
    ELSE
        INSERT INTO groupe (nom_groupe, description_groupe, image, couleur, budget_groupe)
        VALUES (p_nom, p_description, p_image, p_couleur, p_budget_groupe);

        INSERT INTO membre_groupe (mail_utilisateur, id_groupe, role)
        VALUES (p_mail_administrateur, LAST_INSERT_ID(), 'Administrateur');
        SET p_success = TRUE; 
    END IF;
END$$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE `creer_notification`(
    IN p_message VARCHAR(300),
    IN p_id_groupe INT, 
    IN p_mail_utilisateur VARCHAR(100),
    OUT p_success BOOLEAN 
)
BEGIN
    DECLARE user_exists BOOLEAN;
    DECLARE group_exists BOOLEAN;
    SELECT EXISTS(SELECT 1 FROM utilisateur WHERE mail_utilisateur = p_mail_utilisateur) INTO user_exists;
    SELECT EXISTS(SELECT 1 FROM groupe WHERE id_groupe = p_id_groupe) INTO group_exists;

    IF user_exists AND group_exists THEN
        INSERT INTO notification (message, id_groupe, mail_utilisateur, lue)
        VALUES (p_message, p_id_groupe, p_mail_utilisateur, FALSE);
        SET p_success = TRUE;
    ELSE SET p_success = FALSE;
    END IF;
END$$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE `creer_proposition`(
    IN p_titre VARCHAR(100),
    IN p_description VARCHAR(300),
    IN p_id_groupe INT,
    IN p_mail VARCHAR(100),
    OUT p_success BOOLEAN
)
BEGIN
    DECLARE group_exists BOOLEAN;
    DECLARE user_exists BOOLEAN;
    SELECT EXISTS(SELECT 1 FROM groupe WHERE id_groupe = p_id_groupe) INTO group_exists;
    SELECT EXISTS(SELECT 1 FROM utilisateur WHERE mail_utilisateur = p_mail) INTO user_exists;

    IF group_exists AND user_exists THEN 
        INSERT INTO proposition (titre, description, id_groupe, mail_utilisateur)
        VALUES (p_titre, p_description, p_id_groupe, p_mail);
        SET p_success = TRUE; 
    ELSE SET p_success = FALSE;
    END IF;
END$$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE `envoyer_invitation`(
    IN p_mail_invite VARCHAR(100),  
    IN p_role ENUM('Administrateur', 'Modérateur', 'Scrutateur', 'Assesseur', 'Décideur', 'Membre'),
    IN p_id_groupe INT,               
    IN p_lien_invitation VARCHAR(200),
    IN p_message VARCHAR(300),          
    OUT p_success BOOLEAN)
BEGIN
    DECLARE group_exists BOOLEAN;
    SELECT EXISTS(SELECT 1 FROM groupe WHERE id_groupe = p_id_groupe) INTO group_exists;

    IF group_exists THEN
        INSERT INTO invitation (mail_invite, role, lien, statut, message, id_groupe)
        VALUES (p_mail_invite, p_role, p_lien_invitation, 'En attente', p_message, p_id_groupe);
        SET p_success = TRUE;
    ELSE SET p_success = FALSE;
    END IF;
END$$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE `gerer_reaction_commentaire`(
    IN p_id_commentaire INT,
    IN p_id_reaction_ancienne INT,
    IN p_id_reaction_nouvelle INT,
    OUT p_success BOOLEAN
)
BEGIN
    DECLARE comment_exists BOOLEAN;
    DECLARE reaction_exists BOOLEAN;
    DECLARE reaction_already_exists BOOLEAN;
    SELECT EXISTS(SELECT 1 FROM commentaire WHERE id_commentaire = p_id_commentaire) INTO comment_exists;
    SELECT EXISTS(SELECT 1 FROM reaction WHERE id_reaction = p_id_reaction_nouvelle) INTO reaction_exists;

    IF p_id_reaction_ancienne IS NOT NULL THEN
        SELECT EXISTS(SELECT 1 FROM reaction_commentaire WHERE id_commentaire = p_id_commentaire AND id_reaction = p_id_reaction_ancienne) INTO reaction_already_exists;
    END IF;

    IF comment_exists AND reaction_exists THEN
        IF p_id_reaction_ancienne IS NOT NULL AND reaction_already_exists THEN
            UPDATE reaction_commentaire
            SET id_reaction = p_id_reaction_nouvelle
            WHERE id_commentaire = p_id_commentaire AND id_reaction = p_id_reaction_ancienne;
            SET p_success = TRUE;

        ELSEIF p_id_reaction_ancienne IS NULL THEN
            INSERT INTO reaction_commentaire (id_commentaire, id_reaction)
            VALUES (p_id_commentaire, p_id_reaction_nouvelle);
            SET p_success = TRUE;

        ELSE SET p_success = FALSE;
        END IF;

    ELSE SET p_success = FALSE;
    END IF;
END$$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE `gerer_reaction_proposition`(
    IN p_id_proposition INT,
    IN p_id_reaction_ancienne INT,
    IN p_id_reaction_nouvelle INT,
    OUT p_success BOOLEAN
)
BEGIN
    DECLARE proposition_exists BOOLEAN;
    DECLARE reaction_exists BOOLEAN;
    DECLARE reaction_already_exists BOOLEAN;
    SELECT EXISTS(SELECT 1 FROM proposition WHERE id_proposition = p_id_proposition) INTO proposition_exists;
    SELECT EXISTS(SELECT 1 FROM reaction WHERE id_reaction = p_id_reaction_nouvelle) INTO reaction_exists;
    SELECT EXISTS(SELECT 1 FROM reaction_proposition WHERE id_proposition = p_id_proposition AND id_reaction = p_id_reaction_ancienne) INTO reaction_already_exists;

    IF proposition_exists AND reaction_exists THEN
        IF p_id_reaction_ancienne IS NOT NULL AND reaction_already_exists THEN
            UPDATE reaction_proposition
            SET id_reaction = p_id_reaction_nouvelle
            WHERE id_proposition = p_id_proposition AND id_reaction = p_id_reaction_ancienne;
            SET p_success = TRUE;

        ELSEIF p_id_reaction_ancienne IS NULL THEN
            INSERT INTO reaction_proposition (id_proposition, id_reaction)
            VALUES (p_id_proposition, p_id_reaction_nouvelle);
            SET p_success = TRUE; 
        ELSE SET p_success = FALSE; 
        END IF;
    ELSE SET p_success = FALSE; 
    END IF;
END$$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE `maj_budget_groupe`(
    IN p_id_groupe INT,
    IN p_budget INT, 
    OUT p_success BOOLEAN 
)
BEGIN
    DECLARE group_exists BOOLEAN;
    SELECT EXISTS(SELECT 1 FROM groupe WHERE id_groupe = p_id_groupe) INTO group_exists;

    IF group_exists THEN
        UPDATE groupe
        SET budget_groupe = p_budget
        WHERE id_groupe = p_id_groupe;
        SET p_success = TRUE;
    ELSE SET p_success = FALSE;
    END IF;
END$$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE `maj_budget_proposition`(
    IN p_id_proposition INT,
    IN p_budget INT, 
    OUT p_success BOOLEAN 
)
BEGIN
    DECLARE proposition_exists BOOLEAN;
    SELECT EXISTS(SELECT 1 FROM proposition WHERE id_proposition = p_id_proposition) INTO proposition_exists;

    IF proposition_exists THEN
        UPDATE proposition
        SET budget_proposition = p_budget
        WHERE id_proposition = p_id_proposition;
        SET p_success = TRUE;
    ELSE SET p_success = FALSE;
    END IF;
END$$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE `maj_budget_theme_groupe`(
    IN p_id_theme INT,
    IN p_budget INT, 
    OUT p_success BOOLEAN 
)
BEGIN
    DECLARE theme_exists BOOLEAN;
    SELECT EXISTS(SELECT 1 FROM theme_groupe WHERE id_theme = p_id_theme) INTO theme_exists;

    IF theme_exists THEN
        UPDATE theme_groupe
        SET budget_theme_groupe = p_budget
        WHERE id_theme = p_id_theme;
        SET p_success = TRUE;
    ELSE SET p_success = FALSE;
    END IF;
END$$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE `maj_decision_proposition`(
    IN p_id_proposition INT,
    IN p_decision ENUM('Acceptée', 'Refusée', 'En attente'), 
    OUT p_success BOOLEAN 
)
BEGIN
    DECLARE proposition_exists BOOLEAN;
    SELECT EXISTS(SELECT 1 FROM proposition WHERE id_proposition = p_id_proposition) INTO proposition_exists;

    IF proposition_exists THEN
        UPDATE proposition
        SET decision = p_decision
        WHERE id_proposition = p_id_proposition;
        SET p_success = TRUE;
    ELSE SET p_success = FALSE;
    END IF;
END$$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE `maj_groupe`(
    IN p_id_groupe INT,
    IN p_nouveau_nom VARCHAR(100),
    IN p_nouvelle_description VARCHAR(300),
    IN p_nouvelle_image VARCHAR(200),
    IN p_nouvelle_couleur VARCHAR(50),
    IN p_nouveau_budget INT,
    OUT p_success BOOLEAN
)
BEGIN
    DECLARE group_exists BOOLEAN;
    SELECT EXISTS(SELECT 1 FROM groupe WHERE id_groupe = p_id_groupe) INTO group_exists;

    IF group_exists THEN
        UPDATE groupe
        SET
            nom_groupe = p_nouveau_nom,
            description_groupe = p_nouvelle_description,
            image = p_nouvelle_image,
            couleur = p_nouvelle_couleur,
            budget_groupe = p_nouveau_budget
        WHERE id_groupe = p_id_groupe;
        SET p_success = TRUE; 

    ELSE SET p_success = FALSE; 
    END IF;
END$$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE `maj_parametre_notif`(
    IN p_mail_utilisateur VARCHAR(100),
    IN p_frequence_notification ENUM('Quotidienne', 'Hebdomadaire'),
    OUT p_success BOOLEAN          
)
BEGIN
    DECLARE user_exists BOOLEAN;
    SELECT EXISTS(SELECT 1 FROM utilisateur WHERE mail_utilisateur = p_mail_utilisateur) INTO user_exists;

    IF user_exists THEN
        UPDATE parametre_notif
        SET frequence_notification = p_frequence_notification
        WHERE mail_utilisateur = p_mail_utilisateur;
        SET p_success = TRUE;
    ELSE SET p_success = FALSE;
    END IF;
END$$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE `maj_utilisateur`(
    IN p_mail VARCHAR(100),
    IN p_nouveau_mdp VARCHAR(255),
    IN p_nouveau_nom VARCHAR(100),
    IN p_nouveau_prenom VARCHAR(100),
    IN p_nouvelle_adresse VARCHAR(100),
    IN p_nouvelle_photo_profil VARCHAR(255),
    IN p_frequence_notification ENUM('Quotidienne', 'Hebdomadaire'),
    OUT p_success BOOLEAN
)
BEGIN
    DECLARE user_exists BOOLEAN;
    SELECT EXISTS(SELECT 1 FROM utilisateur WHERE mail_utilisateur = p_mail) INTO user_exists;

    IF user_exists THEN
        UPDATE utilisateur
        SET
            mdp_utilisateur = p_nouveau_mdp,
            nom_utilisateur = p_nouveau_nom,
            prenom_utilisateur = p_nouveau_prenom,
            adresse_utilisateur = p_nouvelle_adresse,
            photo_profil = p_nouvelle_photo_profil,
            frequence_notification = p_frequence_notification
        WHERE mail_utilisateur = p_mail;
        SET p_success = TRUE;

    ELSE SET p_success = FALSE; 
    END IF;
END$$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE `marquer_notification_lue`(
    IN p_id_notification INT,           
    OUT p_success BOOLEAN 
)
BEGIN
    DECLARE notification_exists BOOLEAN;
    SELECT EXISTS(SELECT 1 FROM notification WHERE id_notification = p_id_notification) INTO notification_exists;

    IF notification_exists THEN
        UPDATE notification
        SET lue = TRUE
        WHERE id_notification = p_id_notification;
        SET p_success = TRUE;
    ELSE SET p_success = FALSE;
    END IF;
END$$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE `repondre_invitation`(
    IN p_id_invitation INT,             
    OUT p_success BOOLEAN                
)
BEGIN
    DECLARE invitation_exists BOOLEAN;
    SELECT EXISTS(SELECT 1 FROM invitation WHERE statut = 'En attente') INTO invitation_exists;

    IF invitation_exists THEN
        UPDATE invitation
        SET statut = 'Acceptée'
        WHERE id_invitation = p_id_invitation;
        SET p_success = TRUE;
    ELSE SET p_success = FALSE;
    END IF;
END$$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE `signaler_commentaire`(
    IN p_id_commentaire INT,            
    IN p_id_signalement INT,             
    IN p_mail_utilisateur VARCHAR(100),  
    OUT p_success BOOLEAN
)
BEGIN
    DECLARE comment_exists BOOLEAN;
    DECLARE signalement_exists BOOLEAN;
    DECLARE user_exists BOOLEAN;
    SELECT EXISTS(SELECT 1 FROM commentaire WHERE id_commentaire = p_id_commentaire) INTO comment_exists;
    SELECT EXISTS(SELECT 1 FROM signalement WHERE id_signalement = p_id_signalement) INTO signalement_exists;
    SELECT EXISTS(SELECT 1 FROM utilisateur WHERE mail_utilisateur = p_mail_utilisateur) INTO user_exists;

    IF comment_exists AND signalement_exists AND user_exists THEN
        INSERT INTO signalement_commentaire (id_commentaire, id_signalement, mail_utilisateur)
        VALUES (p_id_commentaire, p_id_signalement, p_mail_utilisateur);
        SET p_success = TRUE;
    ELSE SET p_success = FALSE;
    END IF;
END$$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE `signaler_proposition`(
    IN p_id_proposition INT,            
    IN p_id_signalement INT,            
    IN p_mail_utilisateur VARCHAR(100),
    OUT p_success BOOLEAN 
)
BEGIN
    DECLARE proposition_exists BOOLEAN;
    DECLARE signalement_exists BOOLEAN;
    DECLARE user_exists BOOLEAN;

    SELECT EXISTS(SELECT 1 FROM proposition WHERE id_proposition = p_id_proposition) INTO proposition_exists;
    SELECT EXISTS(SELECT 1 FROM signalement WHERE id_signalement = p_id_signalement) INTO signalement_exists;
    SELECT EXISTS(SELECT 1 FROM utilisateur WHERE mail_utilisateur = p_mail_utilisateur) INTO user_exists;

    IF proposition_exists AND signalement_exists AND user_exists THEN
        INSERT INTO signalement_proposition (id_proposition, id_signalement, mail_utilisateur)
        VALUES (p_id_proposition, p_id_signalement, p_mail_utilisateur);
        SET p_success = TRUE;
    ELSE SET p_success = FALSE;
    END IF;
END$$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE `supprimer_commentaire`(
    IN p_id_commentaire INT,             
    IN p_mail_utilisateur VARCHAR(100),  
    OUT p_success BOOLEAN
)
BEGIN
    DECLARE comment_exists BOOLEAN;
    DECLARE is_poster BOOLEAN;
    DECLARE is_admin BOOLEAN;

    SELECT EXISTS(SELECT 1 FROM commentaire WHERE id_commentaire = p_id_commentaire) INTO comment_exists;
    SELECT EXISTS(
        SELECT 1 
        FROM commentaire 
        WHERE id_commentaire = p_id_commentaire 
        AND mail_utilisateur = p_mail_utilisateur
    ) INTO is_poster;


    SELECT EXISTS(
        SELECT 1 
        FROM membre_groupe mg
        JOIN commentaire c ON mg.id_groupe = (SELECT id_groupe FROM proposition WHERE id_proposition = c.id_proposition)
        WHERE c.id_commentaire = p_id_commentaire
        AND mg.mail_utilisateur = p_mail_utilisateur
        AND mg.role = 'Administrateur'
    ) INTO is_admin;

    IF comment_exists AND (is_poster OR is_admin) THEN
        DELETE FROM reaction_commentaire WHERE id_commentaire = p_id_commentaire;
        DELETE FROM signalement_commentaire WHERE id_commentaire = p_id_commentaire;
        DELETE FROM commentaire WHERE id_commentaire = p_id_commentaire;
        SET p_success = TRUE;
        ELSE SET p_success = FALSE;
    END IF;
END$$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE `supprimer_compte_utilisateur`(IN `p_mail` VARCHAR(100), OUT `p_success` BOOLEAN)
BEGIN
    DECLARE user_exists BOOLEAN;
    SELECT EXISTS(SELECT 1 FROM utilisateur WHERE mail_utilisateur = p_mail) INTO user_exists;

    IF user_exists THEN
        DELETE FROM signalement_commentaire WHERE mail_utilisateur = p_mail;
        DELETE FROM signalement_proposition WHERE mail_utilisateur = p_mail;
        DELETE FROM commentaire WHERE mail_utilisateur = p_mail;
        DELETE FROM vote WHERE mail_utilisateur = p_mail;
        DELETE FROM membre_groupe WHERE mail_utilisateur = p_mail;
        UPDATE vote_proposition SET id_proposition = NULL WHERE id_vote_proposition IN (SELECT id_vote_proposition FROM proposition WHERE mail_utilisateur = p_mail);
        DELETE FROM proposition WHERE mail_utilisateur = p_mail;
        DELETE FROM vote_proposition WHERE id_vote_proposition IN (SELECT id_vote_proposition FROM proposition WHERE mail_utilisateur = p_mail);
        DELETE FROM notification WHERE mail_utilisateur = p_mail;
        DELETE FROM utilisateur WHERE mail_utilisateur = p_mail;
        SET p_success = TRUE; 

    ELSE SET p_success = FALSE; 
    END IF;
END$$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE `supprimer_groupe`(
    IN p_id_groupe INT,
    OUT p_success BOOLEAN
)
BEGIN
    DECLARE group_exists BOOLEAN; 
    SELECT EXISTS(SELECT 1 FROM groupe WHERE id_groupe = p_id_groupe) INTO group_exists;

    IF group_exists THEN
        DELETE FROM theme_groupe WHERE id_groupe = p_id_groupe;
        DELETE FROM membre_groupe WHERE id_groupe = p_id_groupe;
        DELETE FROM notification WHERE id_groupe = p_id_groupe;
        DELETE FROM invitation WHERE id_groupe = p_id_groupe;
        DELETE FROM groupe WHERE id_groupe = p_id_groupe;

        SET p_success = TRUE;
    ELSE SET p_success = FALSE; 
    END IF;
END$$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE `supprimer_proposition`(
    IN p_id_proposition INT,
    OUT p_success BOOLEAN
)
BEGIN
    DECLARE proposition_exists BOOLEAN;
    SELECT EXISTS(SELECT 1 FROM proposition WHERE id_proposition = p_id_proposition) INTO proposition_exists;

    IF proposition_exists THEN
        DELETE FROM reaction_proposition WHERE id_proposition = p_id_proposition;
        DELETE FROM vote_proposition WHERE id_proposition = p_id_proposition;
        DELETE FROM signalement_proposition WHERE id_proposition = p_id_proposition;
        DELETE FROM theme_proposition WHERE id_proposition = p_id_proposition;
        DELETE FROM commentaire WHERE id_proposition = p_id_proposition;
        DELETE FROM proposition WHERE id_proposition = p_id_proposition;
        SET p_success = TRUE;

    ELSE SET p_success = FALSE;
    END IF;
END$$
DELIMITER ;



--Nouvelle proposition
DELIMITER /
CREATE TRIGGER notif_nouvelle_proposition
AFTER INSERT ON proposition
FOR EACH ROW
BEGIN
    INSERT INTO notification (message, id_groupe, mail_utilisateur)
    SELECT CONCAT('Une nouvelle proposition a été créée dans le groupe ', g.nom_groupe), NEW.id_groupe, mg.mail_utilisateur
    FROM membre_groupe mg
    JOIN groupe g ON g.id_groupe = mg.id_groupe
    WHERE mg.id_groupe = NEW.id_groupe;
END /
DELIMITER ;


--Proposition supprimée
DELIMITER /
CREATE TRIGGER notif_suppression_proposition
AFTER DELETE ON proposition
FOR EACH ROW
BEGIN
    INSERT INTO notification (message, id_groupe, mail_utilisateur)
    SELECT CONCAT('Une proposition a été supprimée dans le groupe ', g.nom_groupe), OLD.id_groupe, mg.mail_utilisateur
    FROM membre_groupe mg
    JOIN groupe g ON g.id_groupe = mg.id_groupe
    WHERE mg.id_groupe = OLD.id_groupe;
END /

DELIMITER ;


--Commentaire supprimé
DELIMITER /
CREATE TRIGGER notif_suppression_commentaire
AFTER DELETE ON commentaire
FOR EACH ROW
BEGIN
	DECLARE grp INT;
    SELECT id_groupe INTO grp FROM proposition WHERE id_proposition = OLD.id_proposition;
    INSERT INTO notification (message, id_groupe, mail_utilisateur)
    SELECT CONCAT('Un commentaire a été supprimée dans le groupe ', g.nom_groupe), grp, mg.mail_utilisateur
    FROM membre_groupe mg 
    JOIN groupe g ON g.id_groupe = mg.id_groupe
    WHERE mg.id_groupe = grp;
END /
DELIMITER ;