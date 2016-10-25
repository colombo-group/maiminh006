<?php
require 'config/db_config.php';
require 'lib/secure_input.php';

if (isset($_POST['submit'])) {
  //input form data
  $username = secure_input($_POST['username']);
  $email = secure_input($_POST['email']);
  $password = secure_input($_POST['password']);
  $confirmPass = secure_input($_POST['confirm_pass']);
  $fullname = secure_input($_POST['fullname']);
  $day = secure_input($_POST['day']);
  $month = secure_input($_POST['month']);
  $year = secure_input($_POST['year']);
  $birthday = $year . '-' . $month . '-' . $day;
//  $birthday = strval($birthday);
  $phoneNumber = secure_input($_POST['phone_number']);
  $selfInfor = secure_input($_POST['self_infor']);
  $role = secure_input($_POST['role']);
  $ref = secure_input($_POST['ref']);
  $avatar = $_FILES['avatar'];

  //array to notice errors
  $errors = array();
  $fullFields = array($username, $email, $password, $confirmPass, $fullname, $day, $month, $year, $phoneNumber, $role);

  /*   * * Check errors if fields empty ** */
  for ($i = 0; $i < count($fullFields); $i++) {
    if (!$fullFields[$i]) {
      $errors['stop'] = 'You must not allow to empty any fields!';
    }
  }
  /*   * * check length field is valid ** */
  if (!isset($errors['stop'])) {
    if (strlen($username) > 33 || strlen($username) < 6) {
      $errors['username'] = 'Username has at less than 32 characters or more than 6 characters!';
    }
    if (!valid_email($email)) {
      $errors['email'] = 'Your email is invalid!';
    }
    if (strlen($password) > 33 || strlen($password) < 6) {
      $errors['password'] = 'Password has at less than 32 characters or more than 6 characters!';
    }
    if ($password != $confirmPass && !isset($errors['password'])) {
      $errors['passConfirm'] = 'Confirm password don\'t match!';
    }
    if (strlen($fullname) > 255 || strlen($fullname) < 6) {
      $errors['fullname'] = 'Your name has at less than 255 characters or more than 6 characters!';
    }
    if (strlen($phoneNumber) > 13 || strlen($phoneNumber) < 9) {
      $errors['phoneNumber'] = 'Your phone is invalid';
    }
    $roles = array('user', 'admod', 'admin');
    if (!in_array('user', $roles) || !in_array('admod', $roles) || !in_array('admin', $roles)) {
      $errors['role'] = 'Your role is invalid';
    }
    //check ref
    $sqlRef = "SELECT id FROM login WHERE username ='" . $ref . "' or email='" . $ref . "'";
    $refRow = mysqli_num_rows(mysqli_query($conn, $sqlRef));
    if ($ref!='') {
      if ($refRow != 1) {
        $errors['ref'] = 'Your presenter is not exist!';
      }
    }
  }
} //end first SUBMIT

/* * * Continue check user exist or INSERT data to database if there are no errors ** */
if (empty($errors) && isset($_POST['submit'])) {
  $sqlUser = "SELECT username,email FROM login WHERE username='" . $username . "' or email='" . $email . "' ";
  $resUser = mysqli_query($conn, $sqlUser) or die(mysqli_error($conn));
  $rowsUser = mysqli_num_rows($resUser);
  //if it existed, we dont insert
  if ($rowsUser != 0) {
    $errors['existed'] = '<span style="color:red;"> * Your username or email have already existed <br /></span>';
  } else {
    if ($avatar['error'] == 0) {
      require 'upload.php';
      if (move_uploaded_file($tmp_name, $uploadDir) && $continue == 1) {
        //insert data into database
        $sqlLogin = "INSERT INTO `login`(`username`, `email`, `password`, `role`) "
                . " VALUES ('" . $username . "','" . $email . "','" . $password . "','" . $role . "')";
        $resInfor = mysqli_query($conn, $sqlLogin) or die(mysqli_error($conn));
        //fetch id have recently got the last insert sql above
        $fetchId = mysqli_insert_id($conn);
        $sqlInfor = "INSERT INTO `users`(`user_id`,`fullname`, `phone_number`,`avatar`, `birthday`, `self_infor`,`created`,`modified`,`ref`) "
                . " VALUES ('" . $fetchId . "','" . $fullname . "'," . $phoneNumber . ",'" . $name . "','" . $birthday . "','" . $selfInfor . "',CURRENT_DATE,CURRENT_DATE,'" . $ref . "')";
        $sqlInfor = mysqli_query($conn, $sqlInfor) or die(mysqli_error($conn));

        //redirect user if registry successfully!
        header("location:login.php?log=" . md5('login successfully'));
      } else {
        echo '| file is wrong';
      }
    } else {
      $name = 'noimagefound.jpg';
      $sqlLogin = "INSERT INTO `login`(`username`, `email`, `password`, `role`) "
              . " VALUES ('" . $username . "','" . $email . "','" . $password . "','" . $role . "')";
      $resInfor = mysqli_query($conn, $sqlLogin) or die(mysqli_error($conn));
      //fetch id have recently got the last insert sql above
      $fetchId = mysqli_insert_id($conn);
      $sqlInfor = "INSERT INTO `users`(`user_id`,`fullname`, `phone_number`,`avatar`, `birthday`, `self_infor`,`created`,`modified`,`ref`) "
              . " VALUES ('" . $fetchId . "','" . $fullname . "'," . $phoneNumber . ",'" . $name . "','" . $birthday . "','" . $selfInfor . "',CURRENT_DATE,CURRENT_DATE,'" . $ref . "')";
      $sqlInfor = mysqli_query($conn, $sqlInfor) or die(mysqli_error($conn));

      //redirect user if registry successfully!
      header("location:login.php?log=" . md5('login successfully'));
    }
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
        <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" enctype="multipart/form-data">
          <table>
            <tr>
            <caption>__Registry__</caption>
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
              <th>Username: </th>
              <td>
                <input type="text" name="username" value="<?php if (isset($username)) echo $username; ?>"/>
                <?php
                if (isset($errors['username']))
                  echo '<span style="color:red;"> * ' . $errors['username'] . '</span>';
                ?>
              </td>
            </tr>
            <tr>
              <th>Email: </th>
              <td>
                <input type="text" name="email" value="<?php if (isset($email)) echo $email; ?>"/>
                <?php
                if (isset($errors['email']))
                  echo '<span style="color:red;"> * ' . $errors['email'] . '</span>';
                ?>
              </td>
            </tr>
            <tr>
              <th>Password: </th>
              <td>
                <input type="password" name="password" value="<?php if (isset($password)) echo $password; ?>"/>
                <?php
                if (isset($errors['password']))
                  echo '<span style="color:red;"> * ' . $errors['password'] . '</span>';
                ?>
              </td>
            </tr>
            <tr>
              <th>Confirm Password: </th>
              <td>
                <input type="password" name="confirm_pass" value="<?php if (isset($confirmPass)) echo $confirmPass; ?>"/>
                <?php
                if (isset($errors['passConfirm']))
                  echo '<span style="color:red;"> * ' . $errors['passConfirm'] . '</span>';
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
                <select name="day">
                  <?php
                  for ($i = 1; $i <= 31; $i++) {
                    echo '<option value="' . $i . '">' . $i . '</option>';
                  }
                  ?>
                </select>
                <select name="month">
                  <?php
                  for ($i = 1; $i <= 12; $i++) {
                    echo '<option value="' . $i . '">' . $i . '</option>';
                  }
                  ?>
                </select>
                <select name="year">
                  <?php
                  for ($i = 1970; $i < 2016; $i++) {
                    echo '<option value="' . $i . '">' . $i . '</option>';
                  }
                  ?>
                </select>
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
              <th>Roles: </th>
              <td>
                <select name="role">
                  <option value="user">User</option>
                  <option value="admod">Admod</option>
                  <option value="admin">Admin</option>
                </select>
                <?php
                if (isset($errors['role']))
                  echo '<span style="color:red;"> * ' . $errors['role'] . '</span>';
                ?>
              </td>
            </tr>
            <tr>
              <th>Ref: </th>
              <td>
                <input type="text" name="ref" placeholder="Presenter..." value="<?php if (isset($ref)) echo $ref; ?>"/>
                <?php
                if (isset($errors['ref']))
                  echo '<span style="color:red;"> * ' . $errors['ref'] . '</span>';
                ?>
              </td>
            </tr>
            <tr>
              <td></td>
              <td>
                <input type="submit" name="submit" />
                <a href="/forum/login.php"> Already an account!</a>
              </td>

            </tr>

          </table>
        </form>
      </div>


    </div>
  </body>
</html>