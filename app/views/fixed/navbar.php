<nav>
    <a class="navbar-group" href="<?php echo ROUTER_URL?>home">
        <img class="logo" src="public/assets/logo.png" alt="logo Wati Bulle"/>
        <h1 class="navbar-element navbar-title">Wati Bulle</h1>
    </a>

    <div class="navbar-group">
        <?php
        if (isset($_SESSION['userMail'])) {
            $user = User::getById($_SESSION['userMail']);
            echo '<a class="profile-picture-container" href="' . ROUTER_URL . 'profile"> <img class="profile-picture" src="' . BASE_URL . $user->__get('photo_profil') . '"/> </a>';
            echo '<a class="navbar-element" href="' . ROUTER_URL . 'logout">Déconnexion</a>';
        }
        else {
            echo '<a class="navbar-element" href="' . ROUTER_URL . 'login">Connexion</a>';
            echo '<a class="navbar-element" href="' . ROUTER_URL . 'register">Inscription</a>';
        }

        ?>
    </div>
</nav>