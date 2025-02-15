<?php

class User extends Model {

    protected static $id = 'mail_utilisateur';
    protected static $table = 'utilisateur';
    protected static $class = 'User';
    protected $mail_utilisateur;
    protected $mdp_utilisateur;
    protected $nom_utilisateur;
    protected $prenom_utilisateur;
    protected $adresse_utilisateur;
    protected $photo_profil;

    /**
     * Crée un utilisateur dans la base de données
     * @return true|string
     */
    public function create() {

        if (User::getById($this->mail_utilisateur)) {
            return "L'email est déjà utilisé par un autre utilisateur";
        }

        $this->photo_profil = uploadPictureTo($_FILES['photo_profil'], PROFILE_PATH);

        if ($this->photo_profil === false) {
            return "Erreur lors du téléversement de la photo de profil.";
        }

        $pdo = Connexion::pdo();

        $this->mdp_utilisateur = password_hash($this->mdp_utilisateur, PASSWORD_DEFAULT);

        $query = $pdo->prepare('INSERT INTO ' . static::$table . ' (mail_utilisateur, mdp_utilisateur, nom_utilisateur, prenom_utilisateur, adresse_utilisateur, photo_profil) VALUES (:email, :password, :nom, :prenom, :adresse, :photo_profil);');
        $query->bindParam(':email', $this->mail_utilisateur);
        $query->bindParam(':password', $this->mdp_utilisateur);
        $query->bindParam(':nom', $this->nom_utilisateur);
        $query->bindParam(':prenom', $this->prenom_utilisateur);
        $query->bindParam(':adresse', $this->adresse_utilisateur);
        $query->bindParam(':photo_profil', $this->photo_profil);

        if ($query->execute() === false) {
            return "Erreur inconnue lors de la création de l'utilisateur";
        }

        return true;
    }

    /**
     * Authentifie un utilisateur
     * @param $email
     * @param $password
     * @return User|string
     */
    public function authenticate($email, $password) {

        $password = password_hash($password, PASSWORD_DEFAULT);

        $user = User::getById($email);

        if ($user && password_verify($password, $user->mdp_utilisateur)) {
            return $user;
        }

        return "Email ou mot de passe incorrect";
    }

    /**
     * Met à jour les informations de l'utilisateur
     * @param $nom
     * @param $prenom
     * @param $adresse
     * @param $photo_profil
     * @return true|string
     */
    public function update($nom, $prenom, $adresse, $photo_profil, $removePhoto) {
        $this->nom_utilisateur = $nom;
        $this->prenom_utilisateur = $prenom;
        $this->adresse_utilisateur = $adresse;

        $new_photo_profil = uploadPictureTo($photo_profil, PROFILE_PATH);

        if ($new_photo_profil === false) {
            return "Erreur lors du téléversement de la photo de profil.";
        }

        if ($new_photo_profil === PROFILE_PATH . 'default.png') { // Si l'utilisateur n'a pas téléversé de nouvelle photo de profil
            $new_photo_profil = $this->photo_profil; // Conserver l'ancienne photo de profil
        }

        if ($removePhoto && $this->photo_profil !== PROFILE_PATH . 'default.png') {
            $new_photo_profil = PROFILE_PATH . 'default.png';
            unlink(BASE_PATH . $this->photo_profil);
        }

        $this->photo_profil = $new_photo_profil;

        $pdo = Connexion::pdo();
        $query = $pdo->prepare('UPDATE ' . static::$table . ' SET nom_utilisateur = :nom, prenom_utilisateur = :prenom, adresse_utilisateur = :adresse, photo_profil = :photo_profil WHERE mail_utilisateur = :email;');
        $query->bindParam(':nom', $this->nom_utilisateur);
        $query->bindParam(':prenom', $this->prenom_utilisateur);
        $query->bindParam(':adresse', $this->adresse_utilisateur);
        $query->bindParam(':photo_profil', $this->photo_profil);
        $query->bindParam(':email', $this->mail_utilisateur);

        if ($query->execute() === false) {
            return "Erreur inconnue lors de la mise à jour de l'utilisateur";
        }

        return true;
    }


    /**
     * Supprime un utilisateur
     * @return true|string
     */
    public function delete() {
        $pdo = Connexion::pdo();
        $query = $pdo->prepare('CALL supprimer_compte_utilisateur(:email, @success);');
        $query->bindParam(':email', $this->mail_utilisateur);

        if ($query->execute() === false) {
            return "Erreur inconnue lors de la suppression de l'utilisateur";
        }

        $result = $pdo->query("SELECT @success AS success")->fetch(PDO::FETCH_ASSOC);

        if ($result['success']) {
            return true;
        } else {
            return "Erreur inconnue lors de la suppression de l'utilisateur";
        }
    }


    /**
     * Récupère les groupes auxquels l'utilisateur appartient
     * @param $id
     * @return Array | false
     */
    public static function getGroups($id) {
        $pdo = Connexion::pdo();
        $query = $pdo->prepare('SELECT * FROM groupe WHERE id_groupe IN (SELECT id_groupe FROM membre_groupe WHERE mail_utilisateur = :id);');
        $query->bindParam(':id', $id);
        $query->setFetchmode(PDO::FETCH_CLASS, 'Group');
        $query->execute();
        return $query->fetchAll();
    }

    /**
     * Récupère les invitations de l'utilisateur
     * @param $id_user
     * @return Array | false
     */
    public static function getInvitations($mail_utilisateur) {
        $pdo = Connexion::pdo();
        $query = $pdo->prepare('SELECT * FROM invitation WHERE mail_invite = :mail_utilisateur;');
        $query->bindParam(':mail_utilisateur', $mail_utilisateur);
        $query->setFetchmode(PDO::FETCH_CLASS, 'Invitation');
        $query->execute();
        return $query->fetchAll();

    }


}