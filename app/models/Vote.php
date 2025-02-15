<?php

class Vote extends Model {

    protected static $id = 'id_vote';
    protected static $table = 'vote';
    protected static $class = 'Vote';
    protected $id_vote;
    protected $id_vote_proposition;
    protected $mail_utilisateur;
    protected $valeur_vote;

    public static function create($id_vote_proposition, $mail_utilisateur, $valeur_vote) {
        $pdo = Connexion::pdo();

        $query = $pdo->prepare('INSERT INTO ' . static::$table . ' (id_vote_proposition, mail_utilisateur, valeur_vote) VALUES (:id_vote_proposition, :mail_utilisateur, :valeur_vote);');
        $query->bindParam(':id_vote_proposition', $id_vote_proposition);
        $query->bindParam(':mail_utilisateur', $mail_utilisateur);
        $query->bindParam(':valeur_vote', $valeur_vote);

        if ($query->execute() === false) {
            return "Erreur inconnue lors de la création du vote";
        }

        return true;
    }

}