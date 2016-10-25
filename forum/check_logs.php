<?php

$_SESSION['times'] = 0;

function check_logs() {
  $_SESSION['times'] = 1;
  if ($_SESSION['time'] == 1) {
    $last_login = 'CURRENT_TIMESTAMP';
    $times_block = 1;
    //update last time to database
    $updLast = "UPDATE logs SET last_login='".$last_login."', times_block='".$times_block."' ";
    mysqli_query($conn, $updLast);
    
    $sqlOrigin = "SELECT TIMEDIFF(last_login,first_login)/100 AS DiffDate FROM logs";
    $rowOrigin = mysqli_fetch_assoc(mysqli_query($conn, $sqlOrigin));
    print_r($row);
    
  }
}
check_logs();
