<div class="sidebar">
    <a href="dashboard.php" title="Dashboard"><div class='side-lnk'><i class="fas fa-chart-line"></i><div class="side-lnk-text"><?= $dashboard ?></div></div></a>
    <a href="my_page.php" title="My page"><div class='side-lnk'><i class="fa fa-fw fa-home"></i><div class="side-lnk-text"><?= $home ?></div></div></a>
    <a href="allcases.php" title="Active cases"><div class='side-lnk'><i class="fas fa-list-alt"></i><div class="side-lnk-text"><?= $activeCases ?></div></div></a>
    <a href="closedcases.php" title="Closed cases"><div class='side-lnk'><i class="fas fa-door-closed"></i><div class="side-lnk-text"><?= $closedCases ?></div></div></a>
    <a href="new_case.php" title="New case"><div class='side-lnk'><i class="fas fa-door-open"></i><div class="side-lnk-text"><?= $newCase ?></div></div></a>
    <a href="list_contacts.php" title="Customers"><div class='side-lnk'><i class="fas fa-address-card"></i><div class="side-lnk-text"><?= $contact ?></div></div></a>
    <?= $admin->getAdmMenu() ?>
</div>
