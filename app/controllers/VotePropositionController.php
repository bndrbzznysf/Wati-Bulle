<?php
require_once BASE_PATH . '/app/models/VoteProposition.php';
require_once BASE_PATH . '/app/models/Vote.php';

class VotePropositionController {

    public static function create() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $id_groupe = $_POST['id_groupe'];
            $type_vote = $_POST['type_vote'];
            $id_proposition = $_POST['id_proposition'];

            $output = VoteProposition::create($id_proposition, $type_vote);

            if ($output === true) {
                header('Location: ' . ROUTER_URL . 'server&group=' . $id_groupe . '&proposition=' . $id_proposition);
            } else {
                $error = $output;
                echo $error;
            }
        }

        include BASE_PATH . '/app/views/server/vote_proposition/create.php';
    }

    public static function close() {

        if (!isset($_GET['proposition']) || !isset($_GET['group'])) {
            header('Location: ' . ROUTER_URL . 'server');
            exit();
        }

        $id_proposition = (int)($_GET['proposition']);
        $id_groupe = (int)($_GET['group']);

        $voteProposition = VoteProposition::getByProposition($_GET['proposition']);

        $output = $voteProposition->close();

        header('Location: ' . ROUTER_URL . 'server&group=' . $id_groupe . '&proposition=' . $id_proposition);

    }

    public static function vote() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $id_groupe = $_POST['id_groupe'];
            $id_proposition = $_POST['id_proposition'];
            $id_vote_proposition = VoteProposition::getByProposition($id_proposition)->__get('id_vote_proposition');
            $mail_utilisateur = $_SESSION['userMail'];
            $valeur_vote = $_POST['valeur_vote'];

            $output = Vote::create($id_vote_proposition, $mail_utilisateur, $valeur_vote);

            if ($output === true) {
                header('Location: ' . ROUTER_URL . 'server&group=' . $id_groupe . '&proposition=' . $id_proposition);
            } else {
                $error = $output;
                echo $error;
            }
        }

        include BASE_PATH . '/app/views/server/vote_proposition/vote.php';
    }

    public static function result() {
        if (!isset($_GET['proposition']) || !isset($_GET['group'])) {
            return;
        }
        include BASE_PATH . '/app/views/server/vote_proposition/result.php';
    }


    /*public static function display() {
        if (!isset($_GET['proposition'])) {
            return;
        }
        $id_proposition = (int)($_GET['proposition']);
        $votes = VoteProposition::getByProposition($id_proposition);

        foreach ($votes as $vote) {
            $resultat_vote = $vote->__get('resultat_vote');
            $type_vote = $vote->__get('type_vote');
            echo "<div class='vote'><span class='vote-type'> $type_vote </span><span class='vote-result'> $resultat_vote </span></div>";
        }
    }*/
}
?>