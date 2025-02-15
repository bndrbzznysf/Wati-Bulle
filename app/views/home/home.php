<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Plateforme de Démocratie Participative</title>
    <link rel="stylesheet" href="public/CSS/styles.css">
    <link rel="stylesheet" href="public/CSS/home.css">
    <link rel="stylesheet" href="public/CSS/bubble.css">
</head>
<body>

<?php include NAVBAR_PATH; ?>

<div class="container">
    <!-- En-tête -->

    <header class="header">
        <h1>Plateforme de Démocratie Participative</h1>
        <p>Proposez, discutez et votez pour des idées qui comptent.</p>
    </header>

    <!-- Contenu principal -->
    <main class="main-content">
        <section class="cta-section">
            <h2>Rejoignez la communauté</h2>
            <p>Créez un compte pour participer aux discussions et aux votes.</p>
            <div class="cta-buttons">
                <a href="<?php echo ROUTER_URL; ?>login" class="button">Se connecter</a>
                <a href="<?php echo ROUTER_URL; ?>register" class="button">Créer un compte</a>
            </div>
        </section>

        <section class="features-section">
            <h2>Fonctionnalités</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <h3>Proposez des idées</h3>
                    <p>Soumettez des propositions pour améliorer votre communauté.</p>
                </div>
                <div class="feature-card">
                    <h3>Participez aux votes</h3>
                    <p>Exprimez-vous sur les propositions qui vous tiennent à cœur.</p>
                </div>
                <div class="feature-card">
                    <h3>Discutez avec les membres</h3>
                    <p>Échangez des idées et des commentaires avec d'autres membres.</p>
                </div>
            </div>
        </section>
    </main>

    <?php include FOOTER_PATH; ?>

</div>

<?php include BUBBLE_PATH; ?>

</body>
</html>