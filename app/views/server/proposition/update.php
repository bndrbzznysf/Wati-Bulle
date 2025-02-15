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
        <h1>Nouvelle proposition</h1>
    </header>

    <!-- Formulaire d'inscription -->
    <main class="main-content">
        <?php
        if (isset($error)) {
            echo '<div class="error">' . htmlspecialchars($error) . '</div>';
        }
        ?>
        <form action="<?php echo ROUTER_URL; ?>proposition/create<?php if (isset($_GET['group'])) { echo '&group=' . $_GET['group']; } ?>" method="POST" class="auth-form">
            <div class="form-group">
                <label for="titre">Titre</label>
                <input type="text" id="titre" name="titre" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" id="description" name="description" required>
            </div>
            <button type="submit" class="button">Créer une proposition</button>
        </form>
    </main>
</div>

<?php include BUBBLE_PATH; ?>

</body>
</html>