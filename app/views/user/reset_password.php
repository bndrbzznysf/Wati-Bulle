<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation du mot de passe - Plateforme de Démocratie Participative</title>
    <link rel="stylesheet" href="public/CSS/styles.css">
    <link rel="stylesheet" href="public/CSS/formulaire.css">
    <link rel="stylesheet" href="public/CSS/bubble.css">
</head>
<body>

<?php include NAVBAR_PATH; ?>

<div class="container">
    <!-- En-tête -->
    <header class="header">
        <h1>Réinitialisation du mot de passe</h1>
    </header>

    <!-- Formulaire de réinitialisation -->
    <main class="main-content">
        <form action="reset_password_process.php" method="POST" class="auth-form">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <button type="submit" class="button">Envoyer le lien</button>
        </form>
        <p><a href="<?php echo ROUTER_URL; ?>login">Retour à la connexion</a></p>
    </main>
</div>

<?php include BUBBLE_PATH; ?>

</body>
</html>