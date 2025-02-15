<?php
class VoteProposition extends Model {

    protected static $id = 'id_vote_proposition';
    protected static $table = 'vote_proposition';
    protected static $class = 'VoteProposition';

    protected $id_vote_proposition;
    protected $id_proposition;
    protected $resultat_vote;
    protected $type_vote;


    public static function create($id_proposition, $type_vote) {
        $pdo = Connexion::pdo();
        $query = $pdo->prepare('INSERT INTO vote_proposition (id_proposition, type_vote) VALUES (:id_proposition, :type_vote);');
        $query->bindParam(':id_proposition', $id_proposition);
        $query->bindParam(':type_vote', $type_vote);

        if ($query->execute() === false) {
            return "Erreur inconnue lors de la création du vote";
        }

        return true;

    }

    
    public function close() {
        $positive = explode('/', $this->type_vote)[0];
        $negative = explode('/', $this->type_vote)[1];

        $resultat_vote = $this->getPositiveVotes() > $this->getNegativeVotes() ? $positive : $negative;
        $pdo = Connexion::pdo();
        $query = $pdo->prepare('UPDATE vote_proposition SET resultat_vote = :resultat_vote WHERE id_vote_proposition = :id_vote_proposition;');
        $query->bindParam(':id_vote_proposition', $this->id_vote_proposition);
        $query->bindParam(':resultat_vote', $resultat_vote);
        if ($query->execute() === false) {
            return "Erreur inconnue lors de la fermeture du vote";
        }
        $query = $pdo->prepare('CALL ajouter_satisfaction(:id_proposition);');
        $query->bindParam(':id_proposition', $this->id_proposition);
        if ($query->execute() === false) {
            return "Erreur lors de l'insertion de la satisfaction";
        }

        return true;
    }

    public static function getByProposition($id_proposition) {
        $pdo = Connexion::pdo();
        $query = $pdo->prepare('SELECT * FROM vote_proposition WHERE id_proposition = :id_proposition;');
        $query->bindParam(':id_proposition', $id_proposition);
        $query->setFetchMode(PDO::FETCH_CLASS, 'VoteProposition');
        $query->execute();
        return $query->fetch();
    }

    /**
     * Récupère le nombre de votes positifs pour une proposition
     * @param $id_vote_proposition
     * @return mixed
     */
    public function getPositiveVotes() {
        $id_vote_proposition = $this->id_vote_proposition;
        $positiveCount = 0;
        $pdo = Connexion::pdo();
        $query = $pdo->prepare('SELECT COUNT(*) FROM vote WHERE id_vote_proposition = :id_vote_proposition AND (valeur_vote = "Pour" OR valeur_vote = "Oui");');
        $query->bindParam(':id_vote_proposition', $id_vote_proposition);
        $query->execute();
        $positiveCount += $query->fetch()[0];
        return $positiveCount;
    }

    /**
     * Récupère le nombre de votes négatifs pour une proposition
     * @param $id_vote_proposition
     * @return mixed
     */
    public function getNegativeVotes() {
        $id_vote_proposition = $this->id_vote_proposition;
        $negativeCount = 0;
        $pdo = Connexion::pdo();
        $query = $pdo->prepare('SELECT COUNT(*) FROM vote WHERE id_vote_proposition = :id_vote_proposition AND (valeur_vote = "Contre" OR valeur_vote = "Non");');
        $query->bindParam(':id_vote_proposition', $id_vote_proposition);
        $query->execute();
        $negativeCount += $query->fetch()[0];
        return $negativeCount;
    }

}
?>