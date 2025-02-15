<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Plateforme de Démocratie Participative</title>
    <link rel="stylesheet" href="public/CSS/styles.css">
    <link rel="stylesheet" href="public/CSS/formulaire.css">
    <link rel="stylesheet" href="public/CSS/bubble.css">
</head>
<body>

<?php include NAVBAR_PATH; ?>

<div class="container">
    <!-- En-tête -->
    <header class="header">
        <h1>Invitations en attentes</h1>
        <a href="<?php echo ROUTER_URL; ?>server" class="button" style="margin-inline: auto;">Retour</a>
    </header>

    <!-- Formulaire de connexion -->
    <main class="main-content">

        <?php UserController::displayInvitations($_SESSION['userMail']); ?>

    </main>

    <?php include FOOTER_PATH; ?>

</div>

<?php include BUBBLE_PATH; ?>

</body>
</html>