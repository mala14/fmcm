<?php
    $todo = new Todo($pdo);
?>
<nav class="navbar">
    <div class="navbar-row">
        <?= $todo->getNavBar() ?>
    </div>
</nav>
<div class="nav-row"></div>
<article class="main-data">
