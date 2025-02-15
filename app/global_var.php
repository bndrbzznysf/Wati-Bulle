<?php

/*
 * Global variables
 *
 * ATTENTION BASE_PATH != BASE_URL
 * BASE_PATH est le chemin absolu du projet sur disque
 * BASE_URL est l'URL de base du projet
 *
 * BASE_URL est utile pour les liens dans les vues (href, src, etc.)
 * BASE_PATH est utile pour inclure des fichiers PHP (require, include)
 */

if (!defined('BASE_URL')) {
    define('BASE_URL', '/saes3-ibendar/Wati-Bulle');
}

if (!defined('BASE_PATH')) {
    define('BASE_PATH', __DIR__ . '/..');
}

if (!defined('ROUTER_URL')) {
    define('ROUTER_URL', BASE_URL . '/index.php?route=');
}

if (!defined('NAVBAR_PATH')) {
    define('NAVBAR_PATH', BASE_PATH . '/app/views/fixed/navbar.php');
}

if (!defined('FOOTER_PATH')) {
    define('FOOTER_PATH', BASE_PATH . '/app/views/fixed/footer.php');
}

if (!defined('BUBBLE_PATH')) {
    define('BUBBLE_PATH', BASE_PATH . '/app/views/fixed/bubble.php');
}

if (!defined('PROFILE_PATH')) {
    define('PROFILE_PATH', '/public/profilePictures/');
}

if (!defined('SERVER_PATH')) {
    define('SERVER_PATH', '/public/serverPictures/');
}