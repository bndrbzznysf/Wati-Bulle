<?php

class Invitation extends Model {

    protected static $id = 'id_invitation';
    protected static $table = 'invitation';
    protected static $class = 'Invitation';

    protected $id_invitation;
    protected $mail_invite;
    protected $lien;
    protected $statut;
    protected $message;
    protected $id_groupe;
    protected $role;


    /**
     * Crée une invitation dans la base de données
     * @param $mail_invite
     * @param $message
     * @param $id_groupe
     * @param $role
     * @return true|string
     */
    public static function create($mail_invite, $message, $id_groupe, $role)
    {

        $pdo = Connexion::pdo();

        // Créer un lien unique pour l'invitation
        $lien = 'https://' . $_SERVER['HTTP_HOST'] . BASE_URL . '/group/invitation/' . uniqid();

        $query = $pdo->prepare('CALL envoyer_invitation(:mail_invite, :role, :id_groupe, :lien, :message, @success);');
        $query->bindParam(':mail_invite', $mail_invite);
        $query->bindParam(':role', $role);
        $query->bindParam(':id_groupe', $id_groupe);
        $query->bindParam(':lien', $lien);
        $query->bindParam(':message', $message);

        $query->execute();

        // Récupérer la valeur de @success
        $result = $pdo->query("SELECT @success AS success")->fetch(PDO::FETCH_ASSOC);

        if (!$result['success']) {
            return "Erreur lors de l'envoi de l'invitation";
        }

        return true;

    }

    public function accept($groupe, $user, $role) {

        $pdo = Connexion::pdo();
        $query = $pdo->prepare('CALL repondre_invitation(:id_invitation, @success);');
        $query->bindParam(':id_invitation', $this->id_invitation);
        $query->execute();
        $result = $pdo->query("SELECT @success AS success")->fetch(PDO::FETCH_ASSOC);

        if (!$result['success']) {
            return "Erreur lors de l'acceptation de l'invitation";
        }

        $groupe->addMember($user, $role);

        Invitation::deleteById($this->id_invitation);

        return true;

    }

    
    public function decline() {

        $pdo = Connexion::pdo();
        $query = $pdo->prepare('CALL repondre_invitation(:id_invitation, @success);');
        $query->bindParam(':id_invitation', $this->id_invitation);
        $query->execute();
        $result = $pdo->query("SELECT @success AS success")->fetch(PDO::FETCH_ASSOC);

        if (!$result['success']) {
            return "Erreur lors du refus de l'invitation";
        }

        Invitation::deleteById($this->id_invitation);

        return true;

    }

}