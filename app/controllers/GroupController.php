<?php
require_once BASE_PATH . '/app/models/Group.php';
class GroupController {

    public static function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire de création de groupe (Vérification déja faite dans le front)
            $nom_groupe = $_POST['nom_groupe'];
            $description_groupe = $_POST['description_groupe'];
            $image = $_FILES['image'];
            $couleur = $_POST['couleur'];

            $output = Group::create($nom_groupe, $description_groupe, $image, $couleur);

            if (is_string($output) === false) {
                header('Location: ' . ROUTER_URL . 'server');
                exit();
            } else {
                $error = $output;
            }
        }
        include BASE_PATH . '/app/views/server/group/create.php';
    }

    public static function manage() {
        if (!isset($_GET['group'])) {
            header('Location: ' . ROUTER_URL . 'server');
        }

        if (Group::getById($_GET['group'])->getRole($_SESSION['userMail'])  === "Administrateur") {
            include BASE_PATH . '/app/views/server/group/manage.php';
        }
        else {
            echo $_SESSION['userMail']; echo Group::getById($_GET['group'])->__get('mail_administrateur');
            exit();
            header('Location: ' . ROUTER_URL . 'server');
        }
    }

    /**
     * Retire un utilisateur d'un groupe
     * @return void
     */
    public static function removeUser()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . ROUTER_URL . 'server');
            exit();
        }

        if (!isset($_GET['group']) || !isset($_POST['mail_utilisateur'])) {
            header('Location: ' . ROUTER_URL . 'server');
            exit();
        }


        $group = Group::getById($_GET['group']);
        $user = User::getById($_POST['mail_utilisateur']);

        if ($group->getRole($_SESSION['userMail']) !== "Administrateur") {
            header('Location: ' . ROUTER_URL . 'server');
            exit();
        }

        $output = $group->removeUser($user->__get('mail_utilisateur'));

        if (is_string($output)) {
            $error = $output;
            echo $error;
        } else {
            header('Location: ' . ROUTER_URL . 'server&group=' . $_GET['group']);
            exit();
        }

    }


    /**
     * Affiche les groupes de l'utilisateur
     * @return void
     */
    public static function display() {

        $mail_utilisateur = $_SESSION['userMail'];
        $groups = User::getGroups($mail_utilisateur);

        if (count($groups) === 0) {
            return;
        }
        else {
            if (!isset($_GET['group']) || !in_array(Group::getById($_GET['group']), $groups)) {
                header('Location: ' . ROUTER_URL . 'server&group=' . $groups[0]->__get('id_groupe'));
            }
            else {
                $selectedGroup = (int)$_GET['group'];
            }
        }

        foreach ($groups as $group) {
            if ($group->__get('id_groupe') === $selectedGroup) {
                echo "<li class='group-item active'><a href='" . ROUTER_URL . "server&group={$group->__get('id_groupe')}'>{$group->__get('nom_groupe')}</a></li>";
            }
            else {
                echo "<li class='group-item'><a href='" . ROUTER_URL . "server&group={$group->__get('id_groupe')}'>{$group->__get('nom_groupe')}</a></li>";
            }
        }
    }

    /**
     * Affiche les membres d'un groupe
     * @param $idGroup
     */
    public static function displayMembers($idGroup) {

        $userList = Group::getMembers($idGroup);

        foreach ($userList as $user) {
            UserController::display($user);
        }

    }

    /**
     * Affiche les invitations d'un groupe
     * @param $idGroup
     */
    public static function displayInvitations($idGroup) {

        $invitations = Group::getInvitations($idGroup);

        foreach ($invitations as $invitation) {
            InvitationController::displayForGroup($invitation);
        }

    }


}