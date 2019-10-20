<?php
    $todo = new Todo($pdo);
?>
<nav class="navbar">
    <div class="navbar-row">
        <td class="nav-item">
           <a class="nav-link" href="logout.php">Logout</a>
        </td>
        <td class="nav-item">
          <div class="dropdown">
              <div class=" nav-link">New</div>
                  <div class="dropdown-content nav-link">
                      <a href="new_contact.php">Contact</a>
                      <a href="#">Link 2</a>
                      <a href="#">Link 3</a>
                  </div>
          </div>
        </td>
        <td class="nav-item">
          <div class="dropdown">
              <div class=" nav-link">Case</div>
                  <div class="dropdown-content nav-link">
                      <tr><?= $todo->closeCase() ?></tr>
                      <tr><?= $todo->openCase() ?></tr>
                  </div>
          </div>
        </td>
    </div>
</nav>
<article class="main-data">
