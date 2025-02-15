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
        <h1>Lancer un vote</h1>
    </header>

    <!-- Formulaire d'inscription -->
    <main class="main-content">
        <?php
        if (isset($error)) {
            echo '<div class="error">' . htmlspecialchars($error) . '</div>';
        }
        ?>
        <form action="<?php echo ROUTER_URL; ?>vote_proposition/create" method="POST" enctype="multipart/form-data" class="auth-form">
            <input type="hidden" name="id_proposition" value="<?php echo $_GET['proposition'] ?>">
            <input type="hidden" name="id_groupe" value="<?php echo $_GET['group'] ?>">
            
            <div class="form-group">
                <label for="type_vote">Type de vote</label>

                <select id="type_vote" name="type_vote" required>
                    <option value="Pour/Contre">Pour/Contre</option>
                    <option value="Oui/Non">Oui/Non</option>
                </select>
            </div>
            <button type="submit" class="button">Lancer le vote</button>
        </form>
    </main>
</div>

<?php include BUBBLE_PATH; ?>

</body>
</html>