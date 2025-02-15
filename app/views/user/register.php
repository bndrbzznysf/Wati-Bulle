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
        <h1>Inscription</h1>
    </header>

    <!-- Formulaire d'inscription -->
    <main class="main-content">
        <?php
            if (isset($error)) {
                echo '<div class="error">' . htmlspecialchars($error) . '</div>';
            }
        ?>
        <form action="<?php echo ROUTER_URL; ?>register" method="POST" enctype="multipart/form-data" class="auth-form">
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" required>
            </div>
            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="adresse">Adresse postale</label>
                <input type="text" id="adresse" name="adresse" required>
            </div>
            <div class="form-group">
                <label for="photo_profil">Photo de profil (facultative)</label>
                <input type="file" id="photo_profil" name="photo_profil" accept="image/*">
            </div>
            <button type="submit" class="button">Créer un compte</button>
        </form>
        <p>Déjà un compte ? <a href="<?php echo ROUTER_URL; ?>login">Se connecter</a></p>
    </main>
</div>

<?php include BUBBLE_PATH; ?>

</body>
</html>