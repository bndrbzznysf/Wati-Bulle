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
        <h1>Nouvelle invitation</h1>
        <a href="<?php echo ROUTER_URL; ?>group/invitation/manage&group=<?php echo $_GET['group']; ?>" class="button" style="margin-inline: auto;">Retour</a>
    </header>

    <!-- Formulaire d'inscription -->
    <main class="main-content">
        <?php
        if (isset($error)) {
            echo '<div class="error">' . htmlspecialchars($error) . '</div>';
        }
        ?>
        <form action="<?php echo ROUTER_URL; ?>group/invitation/create<?php if (isset($_GET['group'])) { echo '&group=' . $_GET['group']; } ?>" method="POST" class="auth-form">
            <input type="hidden" id="id_groupe" name="id_groupe" value="<?php echo $_GET['group']; ?>">
            <div class="form-group">
                <label for="mail_invite">Mail de l'invité</label>
                <input type="email" id="mail_invite" name="mail_invite" required>
            </div>
            <div class="form-group">
                <label for="message">Message</label>
                <input type="text" id="message" name="message" required>
            </div>
            <div class="form-group">
                <label for="role">Rôle</label>

                <select id="role" name="role" required>
                    <option value="Membre">Membre</option>
                    <option value="Décideur">Décideur</option>
                    <option value="Assesseur">Assesseur</option>
                    <option value="Scrutateur">Scrutateur</option>
                    <option value="Modérateur">Modérateur</option>
                    <option value="Administrateur">Administrateur</option>
                </select>
            </div>
            <button type="submit" class="button">Envoyer l'invitation</button>
        </form>
    </main>
</div>

<?php include BUBBLE_PATH; ?>

</body>
</html>