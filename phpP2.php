<?php

// Database table requirements (from PHP-MySQL-Assignment
//    id - an auto incrementing integer which is the primary key of each video.
//    name - the name of the video, this should be a variable length string 
//           with a maximum length of 255 characters. This is a required field
//           and must be unique.
//    category - the category the video belongs to (action, comedy,
//               drama etc), this should be a variable length string 
//               with a maximum length of 255 characters.
//    length - the length of the movie in minutes, recorded as a positive 
//             integer.
//    rented - this is a boolean value indicating if the video is checked in 
//             or not. It is a required field. When added it should default 
//             to checked in.


echo "<!DOCTYPE html>";
echo "<html>";
echo "\n<head>";
echo "<meta charset='utf-8'>";
echo "\n<title>Video Log</title>";
echo "\n<style type=\"text/css\">\ntable, td, th {\nborder: 1px solid #777;\n}";
echo "\n</style>";
echo "\n</head>";
echo "\n<body>";
// Thanks to Canvas discussion and OSU 290 video
error_reporting(E_ALL);
ini_set('display_errors', 1);




function generateHeader($minVal,$val) {
  echo "\n<thead>";
  echo "\n<tr>";

  // Create header elements for each database parameter
  echo "\n<th>id</th>";
  echo "\n<th>name</th>";
  echo "\n<th>category</th>";
  echo "\n<th>length</th>";
  echo "\n<th>status</th>";
  echo "\n<th></th>"; // put delete button in this column
  echo "\n</tr>";
  echo "\n</thead>";
};


// assume an array of each type is passed in
function generateBody($idArr, $nameArr, $categoryArr, $lengthArr, $statusArr) {
  echo "\n<tbody>";
 
  //creating all cells
  for ($i = 0; $i < sizeof($idArr) ; $i++) {
    //creates a table row
    echo "\n<tr>";
    //create row
    echo '<th>'.$idArr[$i].'</th>';
    echo '<td>'.$nameArr[$i].'</td>';
    echo '<td>'.$categoryArr[$i].'</td>';
    echo '<td>'.$lengthArr[$i].'</td>';
    echo "\n<td><form   >";
    if ($statusArr[$i] === true) {
      echo '<button type=\'submit\' id=chk'.$i.' >Check In<\button>';
    else {
       echo '<button type=\'submit\' id=chk'.$i.' >Check Out<\button>';
    }
    echo "\n</form></td>";
    echo "\n<td><form   >";
    echo '<button type=\'submit\' id=row'.$i.' >Delete<\button>';
    echo "\n</form></td>";
    // end row
    echo "\n</tr>";
  }
  echo "\n</tbody>";
};


echo "\n</body>";
echo "\n</html>";
?>
