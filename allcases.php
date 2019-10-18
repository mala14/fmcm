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
                    <th>Created</th>
                    <th>Case title</th>
                    <th>Assigned to</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Case id</th>
                    <th>Created</th>
                    <th>Case title</th>
                    <th>Assigned to</th>
                </tr>
            </tfoot>
            <tbody>
                <?= $todo->getAllOpenCases() ?>
            </tbody>
        </table>
    </div>
<?php include ("view/footer.php"); ?>
