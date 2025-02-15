<?php

class Group extends Model {

    protected static $id = 'id_groupe';
    protected static $table = 'groupe';
    protected static $class = 'Group';
    protected $id_groupe;
    protected $nom_groupe;
    protected $description_groupe;
    protected $image;
    protected $couleur;
    protected $budget_groupe;

    /**
     * Crée un groupe dans la base de données
     * @return true|string
     */
    public static function create($nom_groupe, $description_groupe, $image, $couleur) {

        if (self::findByName($nom_groupe) !== false) {
            return "Le groupe existe déjà";
        }

        $image = uploadPictureTo($image, SERVER_PATH);

        if ($image === false) {
            return "Erreur lors du téléversement de l'image du serveur.";
        }

        $budget = null;

        $pdo = Connexion::pdo();

        $query = $pdo->prepare('CALL creer_groupe(:nom, :description, :image, :couleur, :mail_administrateur, :budget, @success);');
        $query->bindParam(':nom', $nom_groupe);
        $query->bindParam(':description', $description_groupe);
        $query->bindParam(':image', $image);
        $query->bindParam(':couleur', $couleur);
        $query->bindParam(':budget', $budget);

        if (isset($_SESSION['userMail'])) {
            $query->bindParam(':mail_administrateur', $_SESSION['userMail']);
        } else {
            return "Erreur, tentative de création de groupe sans utilisateur connecté";
        }

        $query->execute();

        // Récupérer la valeur de @success
        $result = $pdo->query("SELECT @success AS success")->fetch(PDO::FETCH_ASSOC);

        if (!$result['success']) {
            return "Erreur inconnue lors de la création du groupe";
        }

        return true;
    }

    /**
     * Met à jour les informations d'un groupe en utilisant la procédure stockée
     * @param string $nouveau_nom
     * @param string $nouvelle_description
     * @param string $nouvelle_image
     * @param string $nouvelle_couleur
     * @return true|string
     */
    public function update($nouveau_nom, $nouvelle_description, $nouvelle_image, $nouvelle_couleur, $removeImage) {
        // Récupérer la connexion PDO
        $pdo = Connexion::pdo();

        $nouvelle_image = uploadPictureTo($nouvelle_image, SERVER_PATH);

        if ($nouvelle_image === false) {
            return "Erreur lors du téléversement de la photo de profil.";
        }

        if ($nouvelle_image === PROFILE_PATH . 'default.png') { // Si l'utilisateur n'a pas téléversé de nouvelle image
            $nouvelle_image = $this->image; // Conserver l'ancienne photo de profil
        }

        if ($removeImage && $this->image !== PROFILE_PATH . 'default.png') {
            $nouvelle_image = SERVER_PATH . 'default.png';
            unlink(BASE_PATH . $this->image);
        }

        // Préparer l'appel à la procédure stockée
        $stmt = $pdo->prepare("CALL maj_groupe(:id_groupe, :nouveau_nom, :nouvelle_description, :nouvelle_image, :nouvelle_couleur, @success)");

        // Binder les paramètres
        $stmt->bindParam(':id_groupe', $this->id_groupe, PDO::PARAM_INT);
        $stmt->bindParam(':nouveau_nom', $nouveau_nom, PDO::PARAM_STR);
        $stmt->bindParam(':nouvelle_description', $nouvelle_description, PDO::PARAM_STR);
        $stmt->bindParam(':nouvelle_image', $nouvelle_image, PDO::PARAM_STR);
        $stmt->bindParam(':nouvelle_couleur', $nouvelle_couleur, PDO::PARAM_STR);

        // Exécuter la procédure stockée
        $stmt->execute();

        // Récupérer la valeur de @success
        $result = $pdo->query("SELECT @success AS success")->fetch(PDO::FETCH_ASSOC);

        // Retourner TRUE si la mise à jour a réussi, sinon FALSE
        if ($result['success']) {
            $this->nom_groupe = $nouveau_nom;
            $this->description_groupe = $nouvelle_description;
            $this->image = $nouvelle_image;
            $this->couleur = $nouvelle_couleur;

            return true;
        } else {
            return "Erreur inconnue lors de la mise à jour du groupe";
        }
    }

    /**
     * Ajoute un membre à un groupe en utilisant la procédure stockée
     * @param string $mail_utilisateur
     * @param string $role
     * @return bool
     */
    public function addMember($mail_utilisateur, $role) {
        // Récupérer la connexion PDO
        $pdo = Connexion::pdo();
        // Préparer l'appel à la procédure stockée
        $stmt = $pdo->prepare("CALL ajouter_membre_groupe(:mail_utilisateur, :id_groupe, :role, @success)");

        // Binder les paramètres
        $stmt->bindParam(':mail_utilisateur', $mail_utilisateur, PDO::PARAM_STR);
        $stmt->bindParam(':id_groupe', $this->id_groupe, PDO::PARAM_INT);
        $stmt->bindParam(':role', $role, PDO::PARAM_STR);

        // Exécuter la procédure stockée
        $stmt->execute();

        // Récupérer la valeur de @success
        $result = $pdo->query("SELECT @success AS success")->fetch(PDO::FETCH_ASSOC);

        if ($result['success']) {
            return true;
        } else {
            return "Erreur inconnue lors de l'ajout du membre au groupe";
        }

    }

    

    /**
     * Supprime un groupe en utilisant la procédure stockée
     * @param int $id_groupe
     * @return bool
     */
    public function delete($id_groupe) {
        // Récupérer la connexion PDO
        $pdo = Connexion::pdo();
        // Préparer l'appel à la procédure stockée
        $stmt = $pdo->prepare("CALL supprimer_groupe(:id_groupe, @success)");

        // Binder les paramètres
        $stmt->bindParam(':id_groupe', $id_groupe, PDO::PARAM_INT);

        // Exécuter la procédure stockée
        $stmt->execute();

        // Récupérer la valeur de @success
        $result = $pdo->query("SELECT @success AS success")->fetch(PDO::FETCH_ASSOC);

        // Retourner TRUE si la suppression a réussi, sinon FALSE
        return (bool) $result['success'];
    }

    /**
     * Récupère un groupe par son Nom
     * @param string $nom_groupe
     * @return Group|false
     */
    public static function findByName($nom_groupe) {
        $pdo = Connexion::pdo();
        $query = $pdo->prepare('SELECT * FROM ' . static::$table . ' WHERE ' . 'nom_groupe' . ' = :nom_groupe;');
        $query->bindParam(':nom_groupe', $nom_groupe);
        $query->setFetchmode(PDO::FETCH_CLASS, static::$class);
        $query->execute();
        if ($query->rowCount() === 0) {
            return false;
        }
        return $query->fetch();
    }

    /**
     * Récupère les propositions d'un groupe
     * @return Array
     */
    public static function getPropositions($id_groupe) {
        $pdo = Connexion::pdo();
        $query = $pdo->prepare('SELECT * FROM proposition WHERE id_groupe = :id_groupe;');
        $query->bindParam(':id_groupe', $id_groupe);
        $query->setFetchmode(PDO::FETCH_CLASS, 'Proposition');
        $query->execute();
        return $query->fetchAll();
    }

    public static function getMembers($id_groupe) {
        $pdo = Connexion::pdo();
        $query = $pdo->prepare('SELECT * FROM utilisateur WHERE mail_utilisateur IN (SELECT mail_utilisateur FROM membre_groupe WHERE id_groupe = :id_groupe);');
        $query->bindParam(':id_groupe', $id_groupe);
        $query->setFetchmode(PDO::FETCH_CLASS, 'User');
        $query->execute();
        return $query->fetchAll();
    }

    public static function getInvitations($id_groupe) {
        $pdo = Connexion::pdo();
        $query = $pdo->prepare('SELECT * FROM invitation WHERE id_groupe = :id_groupe;');
        $query->bindParam(':id_groupe', $id_groupe);
        $query->setFetchmode(PDO::FETCH_CLASS, 'Invitation');
        $query->execute();
        return $query->fetchAll();

    }

    public function getRole($mail_utilisateur) {
        $pdo = Connexion::pdo();
        $query = $pdo->prepare('SELECT role FROM membre_groupe WHERE mail_utilisateur = :mail_utilisateur AND id_groupe = :id_groupe;');
        $query->bindParam(':mail_utilisateur', $mail_utilisateur);
        $query->bindParam(':id_groupe', $this->id_groupe);
        $query->execute();
        return $query->fetch()['role'];
    }

    public function removeUser($mail_utilisateur) {
        $pdo = Connexion::pdo();
        $query = $pdo->prepare('DELETE FROM membre_groupe WHERE mail_utilisateur = :mail_utilisateur AND id_groupe = :id_groupe;');
        $query->bindParam(':mail_utilisateur', $mail_utilisateur);
        $query->bindParam(':id_groupe', $this->id_groupe);
        if ($query->execute() === false) {
            return "Erreur inconnue lors de la suppression de l'utilisateur du groupe";
        }
        return true;

    }

}