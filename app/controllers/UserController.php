<?php
require_once BASE_PATH . '/app/models/User.php';
class UserController
{
    public static function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire (Vérification déja faite dans le front)
            $email = $_POST['email'];
            $password = $_POST['password'];
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $adresse = $_POST['adresse'];
            $photo_profil = $_FILES['photo_profil'];

            // Créer l'utilisateur
            $user = new User(array(
                'mail_utilisateur' => $email,
                'mdp_utilisateur' => $password,
                'nom_utilisateur' => $nom,
                'prenom_utilisateur' => $prenom,
                'adresse_utilisateur' => $adresse,
                'photo_profil' => $photo_profil
            ));

            $output = $user->create();

            if ($output === true) {
                header('Location: ' . ROUTER_URL . 'login');
                exit();
            } else {
                $error = $output;
            }
        }
        include BASE_PATH . '/app/views/user/register.php';
    }

    public static function login() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Authentification de l'utilisateur
            $output = User::getById($email);

            if ($output === false) {
                $error = "Il n'y a pas de compte associé à cet email.";
                include BASE_PATH . '/app/views/user/login.php';
                return;
            }

            if (password_verify($password, $output->__get('mdp_utilisateur'))) {
                // Démarrer la session et rediriger
                session_start();
                $_SESSION['userMail'] = $output->__get('mail_utilisateur');
                header('Location: ' . ROUTER_URL . 'server');
                exit();
            } else {
                $error = "Mot de passe incorrect.";
            }
        }
        include BASE_PATH . '/app/views/user/login.php';
    }

    public static function logout() {
        session_destroy();
        header('Location: ' . ROUTER_URL . 'login');
        exit();
    }

    public static function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = User::getById($_SESSION['userMail']);
            $output = $user->delete();
            if ($output === true) {
                session_destroy();
                header('Location: ' . ROUTER_URL . 'login');
                exit();
            } else {
                $error = $output;
            }
        }
        include BASE_PATH . '/app/views/user/profile.php';

    }

    public static function profile() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = User::getById($_SESSION['userMail']);
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $adresse = $_POST['adresse'];
            if (isset($_POST['remove_photo'])) {
                $remove_photo = true;
            } else {
                $remove_photo = false;
            }
            $photo_profil = $_FILES['photo_profil'];

            // Mettre à jour le profil
            $output = $user->update($nom, $prenom, $adresse, $photo_profil, $remove_photo);
            if (is_string($output)) {
                $error = $output;
            } else {
                $success = "Profil mis à jour avec succès.";
            }
        }
        include BASE_PATH . '/app/views/user/profile.php';
    }

    /**
     * Affiche un utilisateur (une ligne)
     * @param $user
     * @return void
     */
    public static function display($user) {

        $delete_icon = BASE_URL . '/public/assets/trash.png';
        $id_groupe = $_GET['group'];
        $group = Group::getById($_GET['group']);
        $mail_utilisateur = $user->__get('mail_utilisateur');
        $role = $group->getRole($mail_utilisateur);

        echo "<div style='display: flex; align-items: center; margin-bottom: 25px; gap: 20px;'>";
            echo "<img src='" . BASE_URL . $user->__get('photo_profil') . "' alt='Photo de profil' style='width: 50px; height: 50px; border-radius: 50%; margin-right: 20px;'>";
            echo "<p style='font-weight: bold'>{$user->__get('mail_utilisateur')} | {$user->__get('prenom_utilisateur')} | {$user->__get('nom_utilisateur')} | $role </p>";
            if ($mail_utilisateur !== $_SESSION['userMail']) {
                echo "<form method='post' action='" . ROUTER_URL . "group/manage/remove&group=" . $_GET['group'] . "'>";
                    echo "<div>";
                        echo "<input type='hidden' name='mail_utilisateur' value='$mail_utilisateur'>";
                        echo "<button type='submit' style='background-color: var(--rouge); width: 32px; padding: 4px; border-radius: 4px; aspect-ratio: 1; border: none !important;'><img src='$delete_icon' alt='Delete' style='width: 24px; aspect-ratio: 1;'/></button>";
                    echo "</div>";
                echo "</form>";
            }
        echo "</div>";

    }


    public static function quitGroup()
    {

        if (!isset($_GET['group']) || !isset($_SESSION['userMail'])) {
            header('Location: ' . ROUTER_URL . 'server');
            exit();
        }


        $group = Group::getById($_GET['group']);
        $user = User::getById($_SESSION['userMail']);

        if ($_SESSION['userMail'] !== $user->__get('mail_utilisateur')) {
            header('Location: ' . ROUTER_URL . 'server');
            exit();
        }

        $output = $group->removeUser($user->__get('mail_utilisateur'));

        if (is_string($output)) {
            $error = $output;
            echo $error;
        } else {
            header('Location: ' . ROUTER_URL . 'server');
            exit();
        }

    }


    /**
     * Affiche les invitations en attente de l'utilisateur
     * @param $id_utilisateur
     * @return void
     */
    public static function displayInvitations($id_utilisateur) {

        $invitations = User::getInvitations($id_utilisateur);

        foreach ($invitations as $invitation) {
            InvitationController::displayForUser($invitation);
        }

    }


}
