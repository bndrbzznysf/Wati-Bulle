<?php

class Comment extends Model {

    protected static $id = 'id_commentaire';
    protected static $table = 'commentaire';
    protected static $class = 'Comment';
    protected $id_commentaire;
    protected $id_groupe;
    protected $contenu_commentaire;
    protected $date_commentaire;
    protected $id_proposition;
    protected $mail_utilisateur;

    /**
     * Crée un commentaire dans la base de données
     * @return true|string
     */
    public static function create($contenu_commentaire, $id_proposition, $mail_utilisateur) {

        $pdo = Connexion::pdo();

        $query = $pdo->prepare('CALL ajouter_commentaire(:contenu_commentaire, :id_proposition, :mail_utilisateur, @success);');
        $query->bindParam(':contenu_commentaire', $contenu_commentaire);
        $query->bindParam(':id_proposition', $id_proposition);
        $query->bindParam(':mail_utilisateur', $mail_utilisateur);

        $query->execute();

        // Récupérer la valeur de @success
        $result = $pdo->query("SELECT @success AS success")->fetch(PDO::FETCH_ASSOC);

        if (!$result['success']) {
            return "Erreur inconnue lors de l'envoi du commentaire";
        }

        return true;
    }


    /**
     * Supprime un commentaire en utilisant la procédure stockée
     * @param int $id_commentaire
     * @return bool
     */
    public function delete($id_commentaire) {
        // Récupérer la connexion PDO
        $pdo = Connexion::pdo();
        // Préparer l'appel à la procédure stockée
        $stmt = $pdo->prepare("CALL supprimer_commentaire(:id_commentaire, @success)");

        // Binder les paramètres
        $stmt->bindParam(':id_commentaire', $id_commentaire, PDO::PARAM_INT);

        // Exécuter la procédure stockée
        $stmt->execute();

        // Récupérer la valeur de @success
        $result = $pdo->query("SELECT @success AS success")->fetch(PDO::FETCH_ASSOC);

        // Retourner TRUE si la suppression a réussi, sinon FALSE
        return (bool) $result['success'];
    }



    /**
     * Récupère les commentaires d'une proposition
     * @return Array
     */
    public static function getByProposition($id_proposition) {
        $pdo = Connexion::pdo();
        $query = $pdo->prepare('SELECT * FROM commentaire WHERE id_proposition = :id_proposition;');
        $query->bindParam(':id_proposition', $id_proposition);
        $query->setFetchmode(PDO::FETCH_CLASS, 'Comment');
        $query->execute();
        return $query->fetchAll();
    }


}