<?php
    ob_start(); //To preveht errors when redirecting
    $hasGroup = isset($_GET['group']);
    $hasProposition = isset($_GET['proposition']);
    $userMail = $_SESSION['userMail'];
    if ($hasGroup) {
        $isAdmin = Group::getById($_GET['group'])->getRole($userMail) === "Administrateur";
        $isScrutateur = Group::getById($_GET['group'])->getRole($userMail) === "Scrutateur";
    }
    else {
        $isAdmin = false;
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Serveur - Plateforme de Démocratie Participative</title>

    <style>
        :root {
            --group-color: <?php if ($hasGroup) echo Group::getById($_GET['group'])->__get('couleur'); else echo 'var(--vert-secondaire);'?>;
            --group-color-secondary: <?php if ($hasGroup) echo Group::getById($_GET['group'])->__get('couleur') . '99;'; else echo 'var(--vert-primaire);'?>;
        }
    </style>

    <link rel="stylesheet" href="public/CSS/styles.css">
    <link rel="stylesheet" href="public/CSS/server.css">

</head>
<body>

<?php include NAVBAR_PATH;?>

<div class="container">
    <!-- Barre latérale -->
    <aside class="sidebar left">
        <h2>Groupes</h2>
        <ul class="group-list">
            <?php GroupController::display(); ?>
        </ul>
        <a class="button" href="<?php echo ROUTER_URL ?>group/create">Créer</a>
        <a class="button" href="<?php echo ROUTER_URL ?>invitation/onHold">En attente <?php echo count(User::getInvitations($userMail)); ?></a>
        <?php if ($isAdmin): ?>
            <a class="button" href="<?php echo ROUTER_URL ?>group/manage&group=<?php echo $_GET['group']; ?>">Gérer</a>
        <?php endif; ?>
        <?php if ($hasGroup): ?>
            <a class="button" href="<?php echo ROUTER_URL ?>group/quit&group=<?php echo $_GET['group']; ?>">Quitter</a>
        <?php endif; ?>
    </aside>



    <!-- Contenu principal -->
    <main class="main-content">
        <!-- Section des propositions -->
        <section class="proposal-section">
            <?php if ($hasProposition): ?>
                <h2><?php echo PropositionController::getTitle($_GET['proposition']); ?></h2>
                <p> <?php echo PropositionController::getDescription($_GET['proposition']); ?> </p>
            <?php else: ?>
                <h2>Aucune proposition sélectionnée</h2>
                <p>Pour continuer, sélectionnez un groupe, puis sélectionnez une proposition.</p>
                <p>Si vous n'avez pas de groupe ou de proposition, veuillez en créer ou rejoindre un groupe déjà existant.</p>
            <?php endif; ?>

        </section>

        <!-- Section du chat -->
        <section class="chat-section">
            <h2>Chat</h2>
            <div class="chat-window">
                <div class="chat-messages">
                    <?php CommentController::display(); ?>
                </div>
                <form action="<?php echo ROUTER_URL . 'comment/create'; ?>" method="POST" class="chat-input">
                    <input type="hidden" name="proposition_id" value="<?php echo isset($_GET['proposition']) ? htmlspecialchars($_GET['proposition']) : ''; ?>">
                    <input type="hidden" name="group_id" value="<?php echo isset($_GET['group']) ? htmlspecialchars($_GET['group']) : ''; ?>">
                    <input type="hidden" name="userMail" value="<?php echo isset($_SESSION['userMail']) ? htmlspecialchars($_SESSION['userMail']) : ''; ?>">
                    <label for="message-input"></label>
                    <textarea id="message-input" name="message" placeholder="Écrivez un message..." required></textarea>
                    <button type="submit" class="button">Envoyer</button>
                </form>
            </div>
        </section>
    </main>

    <aside class="sidebar right">
        <h2>Propositions</h2>
        <ul class="group-list">
            <?php PropositionController::display(); ?>
        </ul>
        <a class="button" href="<?php echo ROUTER_URL ?>proposition/create<?php if ($hasGroup) { echo '&group=' . $_GET['group']; } ?>">Créer</a>
        <?php

            if ($hasProposition) {
                if (Proposition::hasOpenVote($_GET['proposition'])) {
                    echo '<a class="button" href="' . ROUTER_URL . 'vote_proposition/vote&group=' . $_GET['group'] . '&proposition=' . $_GET['proposition'] . '">Voter</a>';
                    if ($isAdmin || $isScrutateur) {
                        echo '<a class="button" href="' . ROUTER_URL . 'vote_proposition/close&group=' . $_GET['group'] . '&proposition=' . $_GET['proposition'] . '">Cloturer</a>';
                    }
                }
                elseif (Proposition::hasClosedVote($_GET['proposition'])) {
                    echo '<a class="button" href="' . ROUTER_URL . 'vote_proposition/result&group=' . $_GET['group'] . '&proposition=' . $_GET['proposition'] . '">Voir les résultats</a>';
                }
                elseif ($isAdmin) {
                    echo '<a class="button" href="' . ROUTER_URL . 'vote_proposition/create&group=' . $_GET['group'] . '&proposition=' . $_GET['proposition'] . '">Lancer un vote</a>';
                }
            }

        ?>
    </aside>
</div>
</body>
</html>