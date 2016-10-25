<?php

/*
 * @type: function
 * @var: data
 * return: boolean
 */

function secure_input($data) 
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  
  return $data;
}

/*
 * check valid email. Example: example@email.com or so on
 * @type: function
 * @var: email
 * @return: bool
 */
function valid_email($email) 
{
  $checkMail = "/^[\w]+([-_\.][\w]+)*@([a-z0-9]+([\.-][a-z][0-9]+)*)+\.[a-z]{2,}$/i";
    $result = preg_match($checkMail,$email);
    if($result && strlen($email)<=100) {
        return true;
    }
    else {
        return false;
    }
}
?>