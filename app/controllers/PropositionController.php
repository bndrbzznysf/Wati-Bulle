<?php
require_once BASE_PATH . '/app/models/Proposition.php';
class PropositionController {

    public static function create($id_groupe) {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $titre = $_POST['titre'];
            $description = $_POST['description'];

            $output = Proposition::create($titre, $description, $id_groupe);

            if (is_string($output) === false) {
                header('Location: ' . ROUTER_URL . 'server');
                exit();
            } else {
                $error = $output;
            }
        }

        include BASE_PATH . '/app/views/server/proposition/create.php';
    }

    public static function display() {
        if (!isset($_GET['group'])) {
            return;
        }

        $groupID = $_GET['group'];

        $propositions = Group::getPropositions($groupID);

        if (count($propositions) === 0) {
            $selectedProposition = -1;
        }
        else {
            if (!isset($_GET['proposition']) || !in_array(Proposition::getById($_GET['proposition']), $propositions)) {
                header('Location: ' . ROUTER_URL . 'server&group=' . $groupID . '&proposition=' . $propositions[0]->__get('id_proposition'));
            }
            else {
                $selectedProposition = (int)$_GET['proposition'];
            }
        }

        foreach ($propositions as $proposition) {
            if ($proposition->__get('id_proposition') === $selectedProposition) {
                echo "<li class='group-item active'><a href='" . ROUTER_URL . "server&group=$groupID&proposition={$proposition->__get('id_proposition')}'>{$proposition->__get('titre')}</a></li>";
            }
            else {
                echo "<li class='group-item'><a href='" . ROUTER_URL . "server&group=$groupID&proposition={$proposition->__get('id_proposition')}'>{$proposition->__get('titre')}</a></li>";
            }
        }
    }

    public static function getTitle($id) {
        $proposition = Proposition::getById($id);
        return $proposition->__get('titre');
    }

    public static function getDescription($id) {
        $proposition = Proposition::getById($id);
        return $proposition->__get('description');
    }

}