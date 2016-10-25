<?php
session_start();
require 'config/db_config.php';
require 'lib/secure_input.php';
require 'upload.php';
//check session user
if(!isset($_SESSION['ID'])) {
  echo '<h1 align="center">You do not have permission to access</h1>';
      exit;
}




$count = 0;
if ($_SESSION['ROLE'] == 'admin') {
  //select all account is user 
  $sqlID = "SELECT id FROM login WHERE role ='admin'";
  $sqlIDRes = mysqli_query($conn, $sqlID);
  while ($rows = mysqli_fetch_array($sqlIDRes)) {
    if ($rows['id'] == $_GET['id']) {
      if ($_GET['id'] == $_SESSION['ID']) {
        $count = 1;
      }
//      echo '<h1 align="center">You do not have permission to access</h1>';
//      exit;
    }
  }
} else if ($_SESSION['ROLE'] == 'admod') {
  $sqlID = "SELECT id FROM login WHERE role ='admin' or role='admod'";
  $sqlIDRes = mysqli_query($conn, $sqlID);
  while ($rows = mysqli_fetch_array($sqlIDRes)) {
    if ($rows['id'] == $_GET['id']) {
      if ($_GET['id'] == $_SESSION['ID']) {
        $count = 1;
      }
//      echo '<h1 align="center">You do not have permission to access</h1>';
//      exit;
    }
  }
} else if ($_SESSION['ROLE'] == 'user') {
  $sqlID = "SELECT id FROM login WHERE role ='user'";
  $sqlIDRes = mysqli_query($conn, $sqlID);
  
  while ($rows = mysqli_fetch_array($sqlIDRes)) {
    if ($rows['id'] == $_GET['id']) {
      if ($_GET['id'] == $_SESSION['ID']) {
        $count = 1;
      }
    }
  }
  
}
if ($count == 0) {
    echo '<h1 align="center">You do not have permission to access</h1>';
    echo $count;
    exit;
  }

$_SESSION['GET_ID'] = $_GET['id'];




if (isset($_POST['submit'])) {
  //input form data
  $fullname = secure_input($_POST['fullname']);
  $day = secure_input($_POST['day']);
  $month = secure_input($_POST['month']);
  $year = secure_input($_POST['year']);
  $birthday = $year . '-' . $month . '-' . $day;
  $phoneNumber = secure_input($_POST['phone_number']);
  $selfInfor = secure_input($_POST['self_infor']);
  //array to notice errors
  $errors = array();
  $fullFields = array($fullname, $day, $month, $year, $phoneNumber);

  /*   * * Check errors if fields empty ** */
  for ($i = 0; $i < count($fullFields); $i++) {
    if (!$fullFields[$i]) {
      $errors['stop'] = 'You must not allow to empty any fields!';
    }
  }
  /*   * * check length field is valid ** */
  if (!isset($errors['stop'])) {
    if (strlen($fullname) > 255 || strlen($fullname) < 3) {
      $errors['fullname'] = 'Your name has at less than 255 characters or more than 3 characters!';
    }
    if (strlen($phoneNumber) > 13 || strlen($phoneNumber) < 9) {
      $errors['phoneNumber'] = 'Your phone is invalid';
    }
  }
} //end first SUBMIT
/* * * Continue check user exist or INSERT data to database if there are no errors ** */
if (empty($errors) && isset($_POST['submit'])) {
  if (move_uploaded_file($tmp_name, $uploadDir)) {
    //update data into database
    $sqlInfor = "UPDATE `users` SET "
            . " fullname='" . $fullname . "',phone_number=" . $phoneNumber . ",birthday='" . $birthday . "',self_infor='" . $selfInfor . "',modified=CURRENT_DATE,avatar='" . $name . "' WHERE user_id=" . $_GET['id'];
    $sqlInfor = mysqli_query($conn, $sqlInfor) or die(mysqli_error($conn));

    //redirect user if registry successfully!
    header("location:/forum/update.php?id=".$_SESSION['GET_ID']);
    echo '<h1>UPDATE SUCCESSFULLY</h1>';
  }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Registry</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" href="/forum/media/css/home.css" />
  </head>
  <body>
    <div class="wrap">
      <div class="registry">
        <form method="post" action="/forum/update.php?id=<?= $_GET['id'] ?>" enctype="multipart/form-data">
          <table>
            <tr>
            <caption>__Update__</caption>
            </tr>
            <tr>
              <td colspan="2">
                <?php
                if (isset($errors['stop']))
                  echo '<span style="color:red;"> *' . $errors['stop'] . '</span>';
                if (isset($errors['existed']))
                  echo '<span style="color:red;"> *' . $errors['existed'] . '</span>';
                ?>
              </td>
            </tr>

            <tr>
              <th>Full Name: </th>
              <td>
                <input type="text" name="fullname" value="<?php if (isset($fullname)) echo $fullname; ?>"/>
                <?php
                if (isset($errors['fullname']))
                  echo '<span style="color:red;"> * ' . $errors['fullname'] . '</span>';
                ?>
              </td>
            </tr>
            <tr>
              <th>Birth day: </th>
              <td id="birthday">
                <input type="text" name="day" placeholder="Day" value="<?php if (isset($day)) echo $day; ?>"/>
                <input type="text" name="month" placeholder="Month" value="<?php if (isset($month)) echo $month; ?>"/>
                <input type="text" name="year" placeholder="Year" value="<?php if (isset($year)) echo $year; ?>"/>
              </td>
            </tr>
            <tr>
              <th>Phone Number: </th>
              <td>
                <input type="text" name="phone_number" value="<?php if (isset($phoneNumber)) echo $phoneNumber; ?>"/>
                <?php
                if (isset($errors['phoneNumber']))
                  echo '<span style="color:red;"> * ' . $errors['phoneNumber'] . '</span>';
                ?>
              </td>
            </tr>
            <tr>
              <th>Avatar: </th>
              <td><input type="file" name="avatar"/></td>
            </tr>
            <tr>
              <th>Introduce Information Yourself: </th>
              <td><textarea name="self_infor"></textarea></td>
            </tr>

            <tr>
              <td></td>
              <td>
                <input type="submit" name="submit" />
              </td>

            </tr>

          </table>
        </form>
      </div>


    </div>
  </body>
</html>