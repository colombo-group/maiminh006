<?php
session_start();
//unset session
unset($_SESSION['ID']);
unset($_SESSION['PAGE']);
//destroy session
session_destroy();
header("location:/forum/index.php");
?>


