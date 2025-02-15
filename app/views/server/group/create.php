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
        <h1>Créer un groupe</h1>
    </header>

    <!-- Formulaire d'inscription -->
    <main class="main-content">
        <?php
        if (isset($error)) {
            echo '<div class="error">' . htmlspecialchars($error) . '</div>';
        }
        ?>
        <form action="<?php echo ROUTER_URL; ?>group/create" method="POST" enctype="multipart/form-data" class="auth-form">
            <div class="form-group">
                <label for="nom_groupe">Nom du groupe</label>
                <input type="text" id="nom_groupe" name="nom_groupe" required>
            </div>
            <div class="form-group">
                <label for="description_groupe">Description</label>
                <input type="text" id="description_groupe" name="description_groupe" required>
            </div>
            <div class="form-group">
                <label for="couleur">Couleur</label>
                <input type="color" id="couleur" name="couleur" required>
            </div>
            <div class="form-group">
                <label for="image">Image (facultative)</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>
            <button type="submit" class="button">Créer un groupe</button>
        </form>
    </main>
</div>

<?php include BUBBLE_PATH; ?>

</body>
</html>