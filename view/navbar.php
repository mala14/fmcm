<?php
    $todo = new Todo($pdo);
?>
<nav class="navbar">
    <div class="navbar-row">
        <?= $todo->getNavBar() ?>
    </div>
</nav>
<article class="main-data">
