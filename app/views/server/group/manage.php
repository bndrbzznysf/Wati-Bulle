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
        <h1><?php echo Group::getById($_GET['group'])->__get('nom_groupe');?></h1>
        <a href="<?php echo ROUTER_URL; ?>group/invitation/manage&group=<?php echo $_GET['group']; ?>" class="button" style="margin-inline: auto;">Invitations</a>
        <a href="<?php echo ROUTER_URL; ?>server&group=<?php echo $_GET['group']; ?>" class="button" style="margin-inline: auto;">Retour</a>
    </header>

    <!-- Formulaire de connexion -->
    <main class="main-content">

        <?php GroupController::displayMembers($_GET['group']); ?>

    </main>

    <?php include FOOTER_PATH; ?>

</div>

<?php include BUBBLE_PATH; ?>

</body>
</html>