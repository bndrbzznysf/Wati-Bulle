<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Plateforme de Démocratie Participative</title>
    <link rel="stylesheet" href="public/CSS/styles.css">
    <link rel="stylesheet" href="public/CSS/formulaire.css">
    <link rel="stylesheet" href="public/CSS/bubble.css">
</head>
<body>

<?php include NAVBAR_PATH; ?>

<div class="container">
    <!-- En-tête -->
    <header class="header">
        <h1>Résultat du vote</h1>
    </header>

    <!-- Formulaire d'inscription -->
    <main class="main-content">
        <?php

            $id_proposition = $_GET['proposition'];
            $id_groupe = $_GET['group'];
            $proposition_title = Proposition::getById($id_proposition)->__get('titre');
            $vote_proposition = VoteProposition::getByProposition($id_proposition);

            if ($vote_proposition->__get('resultat_vote') !== null) {
                echo '<h2>' . $proposition_title . '</h2>';
                if (in_array($vote_proposition->__get('resultat_vote'), ['Oui', 'Pour']) ) {
                    echo '<h2 class="success">La proposition a été acceptée</h2>';
                } else {
                    echo '<h2 class="error">La proposition a été refusée</h2>';
                }
            } else {
                header('Location: ' . ROUTER_URL . 'proposition/vote&group=' . $id_groupe . '&proposition=' . $id_proposition);
            }

        ?>
    </main>
</div>

<?php include BUBBLE_PATH; ?>

</body>
</html>