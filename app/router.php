<?php

session_start();

include 'global_var.php';

require BASE_PATH . '/app/models/Model.php';
require_once BASE_PATH . '/app/config/connexion.php';
require_once BASE_PATH . '/app/controllers/UserController.php';
require_once BASE_PATH . '/app/controllers/handlePhotoUpload.php';
require_once BASE_PATH . '/app/controllers/GroupController.php';
require_once BASE_PATH . '/app/controllers/PropositionController.php';
require_once BASE_PATH . '/app/controllers/CommentController.php';
require_once BASE_PATH . '/app/controllers/InvitationController.php';
require_once BASE_PATH . '/app/controllers/VotePropositionController.php';


// Récupérer l'URL demandée
if (isset($_GET['route'])) {
    $request = $_GET['route'];
} else {
    $request = '';
}

$request = explode('&', $request)[0];

if (isset($_SESSION['userMail']) && !in_array($request, ['profile', 'logout', 'delete', 'server', 'group/create', 'group/quit', 'proposition/create', 'invitation/onHold', 'invitation/decline', 'invitation/accept', 'group/manage', 'group/manage/remove', 'group/invitation/manage', 'group/invitation/create', 'vote_proposition/create', 'vote_proposition/close', 'vote_proposition/vote', 'vote_proposition/result'])) {
    header('Location: ' . ROUTER_URL . 'server');
}

if (!isset($_SESSION['userMail']) && in_array($request, ['profile', 'logout', 'delete', 'server', 'group/create', 'group/quit', 'proposition/create', 'invitation/onHold', 'invitation/decline', 'invitation/accept', 'group/manage', 'group/manage/remove', 'group/invitation/manage', 'group/invitation/create', 'vote_proposition/create', 'vote_proposition/close', 'vote_proposition/vote', 'vote_proposition/result'])) {
    header('Location: ' . ROUTER_URL . 'login');
}

// Gérer les routes
switch ($request) {
    // Page d'accueil
    case '':
    case 'home':
        include __DIR__ . '/views/home/home.php';
        break;

    // Pages d'authentification
    case 'login':
        UserController::login();
        break;
    case 'register':
        UserController::register();
        break;
    case 'logout':
        UserController::logout();
        break;
    case 'delete':
        UserController::delete();
        break;

    // Page de profil
    case 'profile':
        UserController::profile();
        break;

    // Page du serveur
    case 'server':
        if (isset($_GET['group'])) {
            $hasAccess = false;
            $groups = User::getGroups($_SESSION['userMail']);
            foreach ($groups as $group) {
                if ($group->__get('id_groupe') == $_GET['group']) {
                    $hasAccess = true;
                    break;
                }
            }
            if (!$hasAccess) {
                header('Location: ' . ROUTER_URL . 'server');
            }
        }

        if (isset($_GET['proposition'])) {

            $exist = false;
            $propositions = Group::getPropositions($_GET['group']);
            foreach ($propositions as $proposition) {
                if ($proposition->__get('id_proposition') == $_GET['proposition']) {
                    $exist = true;
                    break;
                }
            }
            if (!$exist) {
                header('Location: ' . ROUTER_URL . 'server&group=' . $_GET['group']);
            }
        }

        include __DIR__ . '/views/server/server.php';
        break;

    case 'group/create':
        GroupController::create();
        break;

    case 'group/manage':
        GroupController::manage();
        break;

    case 'group/manage/remove':
        GroupController::removeUser();
        break;

    case 'group/quit':
        UserController::quitGroup();    

    case 'proposition/create':
        if (!isset($_GET['group'])) {
            header('Location: ' . ROUTER_URL . 'server');
        }
        PropositionController::create($_GET['group']);
        break;

    case 'group/invitation/manage':
        InvitationController::manage();
        break;

    case 'group/invitation/create':
        InvitationController::create();
        break;

    case 'invitation/onHold':
        include __DIR__ . '/views/server/invitation/onHold.php';
        break;

    case 'invitation/accept':
        InvitationController::accept();
        break;

    case 'invitation/decline':
        InvitationController::decline();
        break;

    case 'comment/create':
        CommentController::create();
        break;

    case 'vote_proposition/create':
        VotePropositionController::create();
        break;

    case 'vote_proposition/close':
        VotePropositionController::close();
        break;

    case 'vote_proposition/result':
        VotePropositionController::result();
        break;

    case 'vote_proposition/vote':
        VotePropositionController::vote();
        break;


    // Route par défaut (404)
    default:
        http_response_code(404);
        echo 'Page non trouvée';
        break;
}
