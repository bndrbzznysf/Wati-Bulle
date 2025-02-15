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
        <h1>Connexion</h1>
    </header>

    <!-- Formulaire de connexion -->
    <main class="main-content">
        <?php
        if (isset($error)) {
            echo '<div class="error">' . htmlspecialchars($error) . '</div>';
        }
        if (isset($success)) {
            echo '<div class="success">' . htmlspecialchars($success) . '</div>';
        }
        ?>
        <form action="<?php echo ROUTER_URL; ?>login" method="POST" class="auth-form">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="button">Se connecter</button>
        </form>
        <p>Pas encore de compte ? <a href="<?php echo ROUTER_URL; ?>register">Créer un compte</a></p>
        <p><a href="<?php echo ROUTER_URL; ?>reset_password">Mot de passe oublié ?</a></p>
    </main>

    <?php include FOOTER_PATH; ?>

</div>

<?php include BUBBLE_PATH; ?>

</body>
</html>