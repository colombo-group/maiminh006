<?php

session_start();
require 'config/db_config.php';
require 'lib/secure_input.php';

if (isset($_GET['page'])) {
  if (is_numeric($_GET['page'])) {
    $page = intval($_GET['page']);
  } else {
    $page = 1;
  }
} else {
  $page = 1;
}
if (isset($_POST['limit'])) {
  $_SESSION['PAGE'] = intval($_POST['limit']);
   
} else if(!isset($_SESSION['PAGE'])) {
  $_SESSION['PAGE'] = 5;
}
$limit = $_SESSION['PAGE'];
function paginateLink($sort,$order, $name) {
  if (isset($_GET['type']) && isset($_GET['order'])) {
    $order = secure_input($_GET['order']);

    if ($order == 'asc') {
      echo "<span><a href=\"/forum/index.php?type=" . $sort . "&order=desc\">" . $name . "</a></span>";
    } else {
      echo "<span><a href=\"/forum/index.php?type=" . $sort . "&order=asc\">" . $name . "</a></span>";
    }
  } else {
    echo "<span><a href=\"/forum/index.php?type=" . $sort . "&order=asc\">" . $name . "</a></span>";
  }
}

if (isset($_GET['type']) && isset($_GET['order'])) {
  $sort = secure_input($_GET['type']);
  $order = secure_input($_GET['order']);
  if ($sort == 'full-name') {
    $sort = 'fullname';
  } else if ($sort == 'birthday') {
    $sort = 'birthday';
  } else if ($sort != 'full-name' && $sort != 'birthday') {
    $sort = 'users.id';
  }
  if($order !='asc' && $order !='desc') {
    $order = 'asc';
  }
} else {
  $sort = 'users.id';
  $order = 'asc';
}
$sqlID = "SELECT id FROM login WHERE active=1 ";
$resID = mysqli_num_rows(mysqli_query($conn, $sqlID));
if ($resID != 0) {
  $total = ceil($resID / $limit);
}
$start = $limit * ($page - 1);
//fetching all row which is active
$sqlInfor = "SELECT * FROM users INNER JOIN login ON users.user_id=login.id"
        . " WHERE active=1 ORDER BY " . $sort . " " . $order . " LIMIT " . $start . "," . $limit . " ";
$sqlInforExec = mysqli_query($conn, $sqlInfor);
?>

