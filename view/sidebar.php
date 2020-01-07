<div class="sidebar">
    <div class="hover"><a href="my_page.php"><div class="side-lnk"><?= $home ?></div></a></div>
    <a href="allcases.php"><div class="side-lnk"><?= $activeCases ?></div></a>
    <a href="closedcases.php"><div class="side-lnk"><?= $closedCases ?></div></a>
    <a href="new_case.php"><div class="side-lnk"><?= $newCase ?></div></a>
    <a href="list_contacts.php"><div class="side-lnk"><?= $contacts ?></div></a>
    <?= $admin->getAdmMenu() ?>
</div>
