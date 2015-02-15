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
include 'errReport.php';

echo "<!DOCTYPE html>";
echo "<html>";
echo "\n<head>";
echo "<meta charset='utf-8'>";
echo "\n<title>Video Log</title>";
echo "\n<style type=\"text/css\">\ntable, td, th {\nborder: 1px solid #777;\n}";
echo "\n</style>";
echo "\n<script src='phpP2.js'></script>";
echo "\n<link rel=\"stylesheet\" href=\"style.css\">";
echo "\n</head>";

echo "\n<body>";

echo "\n<div id=\"newVideo\">";
//echo "\n<form action =\"videoLibrary.php?\" method =\"get\">";
echo "\n<form>";
echo "\n<input type=\"text\" name=\"name\"><label>Name</label><br>";
echo "\n<input type=\"text\" name=\"category\"><label>Category</label><br>";
echo "\n<input type=\"number\" name=\"length\"><label>Length</label><br>";
echo "\n<input type=\"button\" name=\"insert\" value=\"Add Video\" onclick=\"handleInsert()\">";
echo "\n</form>";
echo "\n</div>";

echo "\n<br>";
echo "\n<div id='videoTable'>";
//generateHeader();
echo "\n</div>";
echo "\n<br>";

//echo "\n<div><form action =\"videoLibrary.php? method =\"get\">";
echo "\n<div><form>";
echo "\n<input type='submit' name='deleteAll' onclick='handleDeleteAll()' value = 'Delete All Rows'>";
echo "\n</form></div>";




// assume an array of each type is passed in
// function generateBody($idArr, $nameArr, $categoryArr, $lengthArr, $statusArr) {
  // echo "\n<tbody>";
 
  // //creating all cells
  // for ($i = 0; $i < sizeof($idArr) ; $i++) {
    // //creates a table row
    // echo "\n<tr>";
    // //create row
    // echo '<th>'.$idArr[$i].'</th>';
    // echo '<td>'.$nameArr[$i].'</td>';
    // echo '<td>'.$categoryArr[$i].'</td>';
    // echo '<td>'.$lengthArr[$i].'</td>';
    // echo "\n<td><form   >";
    // if ($statusArr[$i] === true) {
      // echo '<button type=\'submit\' id=chk'.$i.' >Check In<\button>';
    // else {
       // echo '<button type=\'submit\' id=chk'.$i.' >Check Out<\button>';
    // }
    // echo "\n</form></td>";
    // echo "\n<td><form   >";
    // echo '<button type=\'submit\' id=row'.$i.' >Delete<\button>';
    // echo "\n</form></td>";
    // // end row
    // echo "\n</tr>";
  // }
  // echo "\n</tbody>";
// }
// };

echo "\n</body>";
echo "\n</html>";
?>
