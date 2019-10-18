<?php
    require ("config.php");
    include ("view/header.php");
    $todo = new Todo($pdo);
		$users = new User($pdo);
		$login = new Login($pdo);
    $admin = new Admin($pdo);
		$login->checkLogin();
		$login->getSessions();
?>
<?php include ("view/sidebar.php"); ?>
    <div class="caseTbl">
        <table class="tableCase">
            <thead>
                <tr>
                    <th>Case id</th>
                    <th>Case title</th>
                    <th>Created</th>
                    <th>Closed</th>
                    <th>Assigned to</th>
                    <th>Closed by</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Case id</th>
                    <th>Case title</th>
                    <th>Created</th>
                    <th>Closed</th>
                    <th>Assigned to</th>
                    <th>Closed by</th>
                </tr>
            </tfoot>
            <tbody>
                <?= $todo->getClosedCases() ?>
            </tbody>
        </table>
    </div>
<?php include ("view/footer.php"); ?>
