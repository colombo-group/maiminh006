<?php
require 'config/db_config.php';
require 'paginator.php';
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" href="/forum/media/css/home.css" />
  </head>
  <body>
    <div class="wrap">
      <div class="option">
        <div class="sort">
          <span>Sort by:</span> 
          <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" class='sort-form' >
            <select name="limit" onchange="this.form.submit()">
              <option>----</option>
              <option value="5">5</option>
              <option value="10">10</option>
              <option value="20">20</option>
              <option value="50">50</option>
              <option value="100">100</option>
              <noscript><input type="submit" /></noscript>
            </select>
            <!--Sort by name-->
            <?php
            //show link order 

            paginateLink('full-name', 'asc', 'Fullname');
            if ($order == 'asc' && $sort == 'fullname') {
              echo '&darr;';
            } else {
              echo '&uarr;';
            }
            paginateLink('birthday', 'asc', 'Birthday');
            if ($order == 'asc' && $sort == 'birthday') {
              echo '&darr;';
            } else {
              echo '&uarr;';
            }
            ?>
          </form>

          <!--logout user if they logged in or show registry/log in--> 
          <?php
          if (isset($_SESSION['ID'])) {
            echo '<div style="padding-top:15px">';
            echo '<a href="/forum/logout.php">Log out</a>';
            echo '</div>';
          } else {
            echo '<div style="padding-top:15px">';
            echo '<a href="/forum/registry.php">Registry</a>';
            echo ' | ';
            echo '<a href="/forum/login.php">Log in</a>';
            echo '</div>';
          }
          ?>
        </div>
      </div>
      <div class="sort">
        <div class="counter"> 
          <?php
          if (isset($total)) {
            //show previous button
            if ($page == 1) {
              echo 'Prev';
            } else {
              echo '<a href="/forum/index.php?page=' . ($page - 1) . '">Prev</a>';
            }
            //show counter button
            if ($total == 1) {
              echo ' 1 ';
            } else {
              for ($i = 1; $i <= $total; $i++) {
                echo ' ' . '<a href="/forum/index.php?page=' . ($i) . '">' . $i . '</a>' . ' ';
              }
            }
            //show next button
            if ($page == $total) {
              echo 'Next';
            } else {
              echo '<a href="/forum/index.php?page=' . ($page + 1) . '">Next</a>';
            }
          }
          ?>
        </div>
      </div>
      <div class="container">
        <?php while ($fetchRow = mysqli_fetch_assoc($sqlInforExec)): ?>
          <div class="infor">
            <div class="avatar">
              <img src="/forum/media/img/<?= $fetchRow['avatar'] ?>" />
              <?php if (isset($_SESSION['ID'])): ?>
                <p><i>Join: <?= $fetchRow['created'] ?></i> </p>
              <?php endif; ?>
            </div>
            <div class="introduce">
              <p>Full Name: <?= $fetchRow['fullname'] ?></p>
              <p>Information:</p>
              <?php if (isset($_SESSION['ID'])): ?>
                <!--edit yourself-->
                <?php
                if ($fetchRow['id'] == $_SESSION['ID'])
                  echo '<a href="/forum/update.php?id=' . $_SESSION['ID'] . '">Edit Yourself</a>';
                ?>
                <p><i>&nbsp;&nbsp;<?= $fetchRow['self_infor'] ?></i></p>
              <?php else: ?>
                <strong>
                  <a href="/forum/login.php">Login to see full Infors</a>
                </strong>
              <?php endif; ?>
            </div>
            <div class="see-more">
              <?php
              if (isset($_SESSION['ROLE'])) {
                if ($_SESSION['ROLE'] == 'admin' && $_SESSION['ID'] != $fetchRow['id']) {
                  echo '<a href="/forum/update.php?id=' . $fetchRow['id'] . '">Update</a>';
                  echo ' | ';
                  echo '<a href="/forum/disable-account.php?id=' . $fetchRow['id'] . '">Disable</a>';
                  echo ' | ';
                } else if ($_SESSION['ROLE'] == 'admod' && $fetchRow['role'] != 'admin' && $fetchRow['role'] != 'admod') {
                  echo '<a href="/forum/update.php?id=' . $fetchRow['id'] . '">Update</a>';
                  echo ' | ';
                  echo '<a href="/forum/disable-account.php?id=' . $fetchRow['id'] . '">Disable</a>';
                  echo ' | ';
                }
              }
              echo '<a href="/forum/detail.php?id=' . $fetchRow['id'] . '" >See more</a>';
              ?>

            </div>



          </div>

<?php endwhile; ?>
      </div>
    </div>
  </body>
</html>
