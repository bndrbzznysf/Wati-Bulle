<?php
    // On considère que l'utilisateur est connecté
    $user = User::getById($_SESSION['userMail']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - Plateforme de Démocratie Participative</title>
    <link rel="stylesheet" href="public/CSS/styles.css">
    <link rel="stylesheet" href="public/CSS/formulaire.css">
    <link rel="stylesheet" href="public/CSS/profile.css">
    <link rel="stylesheet" href="public/CSS/bubble.css">
</head>
<body>

<?php include NAVBAR_PATH; ?>

<div class="container">
    <!-- En-tête -->
    <header class="header">
        <h1>Profil</h1>
    </header>

    <!-- Contenu principal -->
    <main class="main-content">
        <?php if (isset($error)) : ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if (isset($success)) : ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <div class="profile-info">
            <!-- Photo de profil -->
            <div class="profile-picture-container">
                <img class="profile-picture" src="<?php echo BASE_URL . $user->__get('photo_profil'); ?>" alt="Photo de profil">
            </div>

            <!-- Informations de l'utilisateur -->
            <div class="profile-details">
                <p><strong>Nom:</strong> <?php echo htmlspecialchars($user->__get('nom_utilisateur')); ?></p>
                <p><strong>Prénom:</strong> <?php echo htmlspecialchars($user->__get('prenom_utilisateur')); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user->__get('mail_utilisateur')); ?></p>
                <p><strong>Adresse:</strong> <?php echo htmlspecialchars($user->__get('adresse_utilisateur')); ?></p>
            </div>
        </div>

        <!-- Formulaire de mise à jour du profil -->
        <h2>Modifier le profil</h2>
        <form action="<?php echo ROUTER_URL; ?>profile" method="POST" enctype="multipart/form-data" class="auth-form">
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($user->__get('nom_utilisateur')); ?>" required>
            </div>
            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($user->__get('prenom_utilisateur')); ?>" required>
            </div>
            <div class="form-group">
                <label for="adresse">Adresse</label>
                <input type="text" id="adresse" name="adresse" value="<?php echo htmlspecialchars($user->__get('adresse_utilisateur')); ?>" required>
            </div>
            <div class="form-group">
                <label for="photo_profil">Photo de profil</label>
                <input type="file" id="photo_profil" name="photo_profil" accept="image/*">
            </div>
            <div class="form-group">
                <label for="">Retirer photo</label>
                <input type="checkbox" id="remove_photo" name="remove_photo" value="on">
            </div>
            <button type="submit" class="button">Mettre à jour</button>
        </form>
        <form action="<?php echo ROUTER_URL; ?>delete" method="POST" class="auth-form">
            <input type="hidden" name="mail_utilisateur" value="<?php echo $_SESSION['userMail']; ?>">
            <button type="submit" class="button" style="background-color: var(--rouge) !important;">Supprimer</button>
        </form>
    </main>

    <?php include FOOTER_PATH; ?>
</div>

<?php include BUBBLE_PATH; ?>

</body>
</html>