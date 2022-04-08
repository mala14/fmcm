<?php
    $navbar = new Navbar($pdo);
?>
<nav class="navbar">
    <div class="navbar-row">
        <?= $navbar->getNavBar() ?>
    </div>
</nav>
<div class="nav-row"></div>
<article class="main-data">
