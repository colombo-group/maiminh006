<?php
session_start();
require 'config/db_config.php';
require 'lib/secure_input.php';
if (isset($_SESSION['ID'])) {
  header("location:/forum/");
}



if (isset($_POST['submit'])) {
  //input form data
  
  $useremail = secure_input($_POST['username']);
  $password = secure_input($_POST['password']);
  if (!isset($useremail) || !isset($password)) {
    echo '<h2 color="red">Wrong username/email or password!</h2>';
  } else {
    $sqlUser = "SELECT * FROM login WHERE (email='" . $useremail . "' or username='" . $useremail . "') and password='" . $password . "' and active=1 ";
    $resUser = mysqli_query($conn, $sqlUser) or die(mysqli_error($conn));
    $rowsUser = mysqli_num_rows($resUser);

    if ($rowsUser != 1) {
      $err = '<span style="color:red;"> * Your username/email or password dont match! <br /></span>';
    } else {
      $fetchRow = mysqli_fetch_assoc($resUser);
      $_SESSION['ID'] = $fetchRow['id'];
      $_SESSION['ROLE'] = $fetchRow['role'];
      //redirect user if login successfully
      header("location:/forum/index.php");
    }
  }
}
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
      <div class="login-success">
        <?php
        if(isset($_GET['log'])) {
          if($_GET['log'] == md5('login successfully')) {
            echo '<p>Registry successfully. Login now!</p>';
          }
        }
        ?>
      </div>
      <div class="login">
        <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
          <table>
            <tr>
              <td colspan="2">
                <?php 
        if(isset($err)) {
          echo $err;
        }
        ?>
              </td>
            </tr>
            <caption>Login</caption>
            <tr>
              <th>Username:</th>
              <td><input type="text" name="username"/></td>
            </tr>
            <tr>
              <th>Password:</th>
              <td><input type="password" name="password"/></td>
            </tr>
            <tr>
              <td></td>
              <td><input type="submit" name="submit"/>
              
              </td>
            </tr>
            <tr>
              <td></td>
              <td><a href="/forum/registry.php"> Registry!</a></td>
            </tr>
            <tr>
              <td></td>
              <td><a href="/forum/index.php"> Home Page </a></td>
            </tr>
          </table>
        </form>
      </div>
    </div>
  </body>
</html>
