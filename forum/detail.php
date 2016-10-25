<?php
session_start();
require 'config/db_config.php';
//check session user
if (!isset($_SESSION['ID'])) {
  echo '<h1 align="center">You do not have permission to access</h1>';
  exit;
}
//fetching infor
$sql = "SELECT * FROM users INNER JOIN login ON users.user_id=login.id WHERE active=1 and user_id =" . $_GET['id'];
$rows = mysqli_fetch_assoc(mysqli_query($conn, $sql));

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
      <div class="container">
        <div class="detail">

          <p><img src="/forum/media/img/<?= $rows['avatar'] ?>"/></p>
          <p>Fullname: <?= $rows['fullname'] ?></p>
          <p>Phone Number: <?= $rows['phone_number'] ?></p>
          <p>Birthday: <?= $rows['birthday'] ?></p>
          <p>Created: <?= $rows['created'] ?></p>
          <p>Modified: <?= $rows['modified'] ?></p>
          <p>Role: <?= $rows['role'] ?></p>
          <?php if(isset($rows['ref']) || empty($rows['ref'])): ?>
          <p>Ref: <?= $rows['ref'] ?></p>
          <?php else: ?>
          <p>Ref: None</p>
          <?php endif; ?>
          <p><a href="/forum/">Home</a></p>


        </div>
      </div>

    </div>

  </body>
</html>