<?php

require_once BASE_PATH . '/app/models/Invitation.php';

class InvitationController {

    public static function create() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $mail_invite = $_POST['mail_invite'];
            $message = $_POST['message'];
            $id_groupe = $_POST['id_groupe'];
            $role = $_POST['role'];

            echo $role;

            $output = Invitation::create($mail_invite, $message, $id_groupe, $role);

            if (is_string($output)) {
                $error = $output;
            }
            else {
                header('Location: ' . ROUTER_URL . 'group/invitation/manage&group=' . $id_groupe);
                exit();
            }

        }
        include BASE_PATH . '/app/views/server/invitation/create.php';
    }

    public static function accept() {

        if (!isset($_GET['invitation'])) {
            header('Location: ' . ROUTER_URL . 'server');
            exit();
        }

        $invitation = Invitation::getById($_GET['invitation']);

        if ($_SESSION['userMail'] !== $invitation->__get('mail_invite')) {
            header('Location: ' . ROUTER_URL . 'server');
            exit();
        }

        $groupe = Group::getById($invitation->__get('id_groupe'));
        $user = $invitation->__get('mail_invite');
        $role = $invitation->__get('role');

        $output = $invitation->accept($groupe, $user, $role);

        if (is_string($output)) {
            $error = $output;
            echo $error;
        }
        else {
            header('Location: ' . ROUTER_URL . 'server');
            exit();
        }

    }

    public static function decline() {

        if (!isset($_GET['invitation'])) {
            header('Location: ' . ROUTER_URL . 'server');
            exit();
        }

        $invitation = Invitation::getById($_GET['invitation']);

        if ($_SESSION['userMail'] !== $invitation->__get('mail_invite')) {
            header('Location: ' . ROUTER_URL . 'server');
            exit();
        }

        $output = $invitation->decline();

        if (is_string($output)) {
            $error = $output;
            echo $error;
        }
        else {
            header('Location: ' . ROUTER_URL . 'server');
            exit();
        }

    }

    public static function manage() {

        if (Group::getById($_GET['group'])->getRole($_SESSION['userMail']) !== "Administrateur") {
            header('Location: ' . ROUTER_URL . 'server');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id_invitation = $_POST['id_invitation'];
            $output = Invitation::deleteById($id_invitation);

            if (is_string($output)) {
                $error = $output;
            }

        }

        include BASE_PATH . '/app/views/server/invitation/manage.php';
    }

    public static function displayForGroup($invitation) {

        $id_invitation = $invitation->__get('id_invitation');
        $mail_invite = $invitation->__get('mail_invite');
        $delete_icon = BASE_URL . "/public/assets/trash.png";
        $role = $invitation->__get('role');

        echo "<div style='margin-bottom: 25px; display: flex; justify-content: center; align-items: center; gap: 25px;'>";
            echo "<p style='font-weight: bold'>$mail_invite</p>";
            echo "<p>{$invitation->__get('message')}</p>";
            echo "<p style='font-weight: bold'>$role</p>";

            echo "<form method='post' action='" . ROUTER_URL . "group/invitation/manage&group=" . $_GET['group'] . "'>";
                echo "<div>";
                    echo "<input type='hidden' name='id_invitation' value='$id_invitation'>";
                    echo "<button type='submit' style='background-color: var(--rouge); width: 32px; padding: 4px; border-radius: 4px; aspect-ratio: 1; border: none !important;'><img src='$delete_icon' alt='Delete' style='width: 24px; aspect-ratio: 1;'/></button>";
                echo "</div>";
            echo "</form>";

        echo "</div>";

    }

    public static function displayForUser($invitation) {

        $id_invitation = $invitation->__get('id_invitation');
        $nom_groupe = Group::getById($invitation->__get('id_groupe'))->__get('nom_groupe');
        $role = $invitation->__get('role');
        $accept_icon = BASE_URL . "/public/assets/accept.png";
        $decline_icon = BASE_URL . "/public/assets/decline.png";
        $accept_url = ROUTER_URL . "invitation/accept&invitation=$id_invitation";
        $decline_url = ROUTER_URL . "invitation/decline&invitation=$id_invitation";

        echo "<div style='margin-bottom: 25px; display: flex; justify-content: center; align-items: center; gap: 25px;'>";
            echo "<p style='font-weight: bold'>$nom_groupe</p>";
            echo "<p>{$invitation->__get('message')}</p>";
            echo "<p style='font-weight: bold'>$role</p>"; 
            echo "<div style='display: flex; gap: 15px; justify-content: center;'>";
                echo "<a href='$accept_url'><img src='$accept_icon' alt='Accept' style='width: 32px; aspect-ratio: 1;'/></a>";
                echo "<a href='$decline_url'><img src='$decline_icon' alt='Decline' style='width: 32px; aspect-ratio: 1;'/></a>";
            echo "</div>";
        echo "</div>";

    }


}