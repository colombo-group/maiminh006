<?php
if($_SERVER["REQUEST_METHOD"] == "POST") {
  $file = $_FILES['avatar'];
  $name = $file['name'];
  $type = $file['type'];
  $size = $file['size'];
  $tmp_name = $file['tmp_name'];
  $continue = 1;
  $maxSize = 1024*1024*2; //max 2MB
  $allowedExts = 'jpg,jpeg,png';
  $inputExt = substr($name,  strrpos($name, '.')+1);
  $individualExt = explode(',', $allowedExts);

  if(!in_array($inputExt, $individualExt)) {
    //comparing between 2 ext
    echo "Only the file as $allowedExts is available";
    $continue = 0;
  }
  //limit file size 2MB
  if($size>=$maxSize) {
    echo "Only the file less than 2MB is available";
    $continue = 0;
  }
  //Only save file name and extension to database
  $fileName = basename($name);
  $uploadDir = '/var/www/html/forum/media/img/'.$name;
  
}
?>

