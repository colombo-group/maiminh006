<?php
session_start();
require 'config/db_config.php'; 
$count = 0;
if ($_SESSION['ROLE'] == 'admin') {
  //select all account is user 
  $sqlID = "SELECT id FROM login WHERE role ='admin'";
  $sqlIDRes = mysqli_query($conn, $sqlID);
  while ($rows = mysqli_fetch_array($sqlIDRes)) {
    if ($rows['id'] == $_GET['id']) {
      echo 'You cant not delete this account';
      $count = 1;
      exit;
    }
  }
  if ($count == 0) {
    $sqlDisable = "UPDATE `login` SET `active`=0 WHERE id=" . $_GET['id'];
    $resDisable = mysqli_query($conn, $sqlDisable);
    echo '<h1 align="center">This account has been disabled</h1>';
    echo '<h1 align="center"><a href="/forum/">Home</a></h1>';
  }
} else if ($_SESSION['ROLE'] == 'admod') {
  $sqlID = "SELECT id FROM login WHERE role ='admin' or role='admod'";
  $sqlIDRes = mysqli_query($conn, $sqlID);
  while ($rows = mysqli_fetch_array($sqlIDRes)) {
    if ($rows['id'] == $_GET['id']) {
      echo 'You cant not delete this account';
      $count = 1;
      exit;
    }
  }
  if ($count == 0) {
    $sqlDisable = "UPDATE `login` SET `active`=0 WHERE id=" . $_GET['id'];
    $resDisable = mysqli_query($conn, $sqlDisable);
    echo '<h1 align="center">This account has been disabled</h1>';
    echo '<h1 align="center"><a href="/forum/">Home</a></h1>';
  }
} else {
  echo '<h1 align="center">You cant not delete this account</h1>';
}
?>

