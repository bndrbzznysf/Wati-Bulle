<?php

include BASE_PATH . '/app/models/User.php';

$groups = User::getGroupsId($_SESSION['email']);

foreach ($groups as $idGroup) {
    echo '<li class="group-item">' . $idGroup . '</li>';
}
?>