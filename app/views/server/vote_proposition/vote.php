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
        <h1>Voter</h1>
    </header>

    <!-- Formulaire d'inscription -->
    <main class="main-content">
        <?php
        if (isset($error)) {
            echo '<div class="error">' . htmlspecialchars($error) . '</div>';
        }
        ?>
        <form action="<?php echo ROUTER_URL; ?>vote_proposition/vote" method="POST" enctype="multipart/form-data" class="auth-form">
            <input type="hidden" name="id_proposition" value="<?php echo $_GET['proposition'] ?>">
            <input type="hidden" name="id_groupe" value="<?php echo $_GET['group'] ?>">

            <div class="form-group">
                <label for="valeur_vote">Valeur de vote</label>
                <?php if (VoteProposition::getByProposition($_GET["proposition"])->__get('type_vote') === 'Pour/Contre'): ?>
                <select id="valeur_vote" name="valeur_vote" required>
                    <option value="Pour">Pour</option>
                    <option value="Contre">Contre</option>
                </select>
                <?php else: ?>
                <select id="valeur_vote" name="valeur_vote" required>
                    <option value="Oui">Oui</option>
                    <option value="Non">Non</option>
                </select>
                <?php endif ?>
            </div>
            <button type="submit" class="button">Envoyer Vote</button>
        </form>
    </main>
</div>

<?php include BUBBLE_PATH; ?>

</body>
</html>