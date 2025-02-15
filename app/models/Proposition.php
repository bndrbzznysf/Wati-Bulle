<?php

class Proposition extends Model {

    protected static $id = 'id_proposition';
    protected static $table = 'proposition';
    protected static $class = 'Proposition';
    protected $id_proposition;
    protected $titre;
    protected $description;
    protected $date_creation;
    protected $duree_discussion_min;
    protected $id_groupe;
    protected $mail_utilisateur;
    protected $budget_proposition;
    protected $satisfaction;
    protected $decision;

    /**
     * Crée un objet proposition et le rajoute à la base de données
     * @param $titre
     * @param $description
     * @param $id_groupe
     * @return string|true
     */
    public static function create($titre, $description, $id_groupe) {

        if (isset($_SESSION['userMail'])) {
            $mail_utilisateur = $_SESSION['userMail'];
        } else {
            return "Erreur, tentative de création de proposition sans utilisateur connecté";
        }

        // Vérifier si l'utilisateur est membre du groupe
        $userGroups = User::getGroups($mail_utilisateur);

        $userGroupsIds = [];
        foreach ($userGroups as $group) {
            $userGroupsIds[] = $group->__get('id_groupe');
        }

        if (in_array($id_groupe, $userGroupsIds) === false) {
            return "ERREUR: Vous n'êtes pas membre de ce groupe veuillez retourner à la page précédente";
        }

        $pdo = Connexion::pdo();

        $query = $pdo->prepare('CALL creer_proposition(:titre, :description, :id_groupe, :mail, @success);');

        $query->bindParam(':titre', $titre);
        $query->bindParam(':description', $description);
        $query->bindParam(':id_groupe', $id_groupe);
        $query->bindParam(':mail', $mail_utilisateur);

        if ($query->execute() === false) {
            return "Erreur inconnue lors de la création de la proposition";
        }

        return true;

    }

    /**
     * Renvoie si oui ou non une proposition a un vote en cours
     * @param $id_proposition
     * @return bool
     */
    public static function hasOpenVote($id_proposition) {
        $pdo = Connexion::pdo();
        $query = $pdo->prepare('SELECT * FROM vote_proposition WHERE id_proposition = :id_proposition AND resultat_vote IS NULL;');
        $query->bindParam(':id_proposition', $id_proposition);
        $query->execute();
        return $query->rowCount() > 0;
    }

    /**
     * Renvoie si oui ou non une proposition a un vote cloturé
     * @param $id_proposition
     * @return bool
     */
    public static function hasClosedVote($id_proposition){
        $pdo = Connexion::pdo();
        $query = $pdo->prepare('SELECT * FROM vote_proposition WHERE id_proposition = :id_proposition AND resultat_vote IS NOT NULL;');
        $query->bindParam(':id_proposition', $id_proposition);
        $query->execute();
        return $query->rowCount() > 0;
    }

}