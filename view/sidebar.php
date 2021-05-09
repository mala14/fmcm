<div class="sidebar">
    <div class="side-lnk"><a href="my_page.php"><i class="fa fa-fw fa-home"></i><div class="side-lnk-text"><?= $home ?></div></a></div>
    <div class="side-lnk"><a href="closedcases.php"><i class="fas fa-door-closed"></i><div class="side-lnk-text"><?= $closedCases ?></div></a></div>
    <div class="side-lnk"><a href="new_case.php"><i class="fas fa-door-open"></i><div class="side-lnk-text"><?= $newCase ?></div></a></div>
    <div class="side-lnk"><a href="list_contacts.php"><i class="fas fa-address-card"></i><div class="side-lnk-text"><?= $contact ?></div></a></div>
    <?= $admin->getAdmMenu() ?>
</div>
