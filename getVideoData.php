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
// Thanks to Canvas discussion and OSU 290 video
error_reporting(E_ALL);
ini_set('display_errors', 1);

$urlStr = "http://web.engr.oregonstate.edu/~huffakco/cs290-assignment5/";

echo "<!DOCTYPE html>";
echo "<html>";
echo "\n<head>";
echo "<meta charset='utf-8'>";
echo "\n<title>Add Video Data</title>";
echo "\n<style type=\"text/css\">\ntable, td, th {\nborder: 1px solid #777;\n}";
echo "\n</style>";
echo "\n</head>";
echo "\n<body>";
echo "<form action =";
echo  $urlStr.'checkVideo.php method =\'post\'>';
echo  "\nName: <input type='text' name='name'><br>";
echo  "\nCategory: <input type='text' name='category'><br>";
echo  "\nLength: <input type='numeric' name='length'><br>";
echo  "\n<input type=\'submit\'>";
echo  "\n</form>";


// function to check for data not null and numeric (use for length)
function chkPosInteger($val, $str)
{
    if (empty($val) && ($val != '0')) {
        return ('Missing parameter '.$str.'.');
    }
    else {
      if (!(is_numeric($val))) {
        return($str.' must be an number.');
      }
      else {
        if ($val >= 0) {
          return($str.' must be an positive.');
        }
      }
    }
    return("");
};

// function to check for data not null or empty or > 255 characters
// use for name or category
function chkNotEmpty($val, $str)
{
    if (empty($val) && ($val != '0')) {
        return ('Missing parameter '.$str.'.');
    }
    else {
      if ($val > 255) {
        return ($str.' entry exceeds 255 characters');
      }
    return("");
};

// function to determine if a name is unique in the database
// get all names from database
// loop through returned names looking for match to latest
// return true if unique, false if found (not unique)
function checkNameUnique($name) {
  // get list of names from database
  if ($videos = $mysqli->query("SELECT name FROM videoLibrary")) {
    echo "Query failed: (" . $stmt->errno . ") " . $stmt->error;
    return(false);
  }
 
  while ($videos->fetch()) {
    if ($videos === $name) {
      return(false);
    }
  }  
  
  return(true);
}



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

// Get input variables
function checkInputs() {
  $result = true;
  
  if (!(isset($_GET["min-multiplicand"]))) {
    echo "<br>Missing parameter min-multiplicand.<br>";
    $result = false;
  } else {
    $chkValue = chkValid($_GET["min-multiplicand"],"min-multiplicand");
    if (!(empty($chkValue) )) {
      echo "<br>$chkValue<br>"; 
      $result = false;
    }
  }
  
  if (!(isset($_GET["max-multiplicand"]))) {
    echo "<br>Missing parameter max-multiplicand.<br>";
    $result = false;
  } else {
    $chkValue = chkValid($_GET["max-multiplicand"],"max-multiplicand");
    if (!(empty($chkValue) )){
      echo "<br>$chkValue<br>"; 
      $result = false;
    }
  }
  
  if (!(isset($_GET["min-multiplier"]))) {
    echo "<br>Missing parameter min-multiplier.<br>";
    $result = false;
  } else {
    $chkValue = chkValid($_GET["min-multiplier"],"min-multiplier");
    if (!(empty($chkValue) )){
      echo "<br>$chkValue<br>"; 
      $result = false;
    }
  }
  
  
  if (!(isset($_GET["max-multiplier"]))) {
    echo "<br>Missing parameter max-multiplier.<br>";
    $result = false;
  } else {
    $chkValue = chkValid($_GET["max-multiplier"],"max-multiplier");
    if (!(empty($chkValue) )){
      echo "<br>$chkValue<br>"; 
      $result = false;
    }
  }
  
  if ($result) {
    //check for max > min
    if ($_GET["max-multiplicand"] < $_GET["min-multiplicand"]) {
      echo "<br>Minimum multiplicand larger than maximum<br>";
      $result = false;
    }
    if ($_GET["max-multiplier"] < $_GET["min-multiplier"]) {
      echo "<br>Minimum multiplier larger than maximum<br>";
      $result = false;
    }
  }
  
  return($result);
}

if (checkInputs()) {
  // get inputs
  $minMultiplicand = (int) $_GET["min-multiplicand"];
  $maxMultiplicand = (int) $_GET["max-multiplicand"];
  $minMultiplier = (int) $_GET["min-multiplier"]; 
  $maxMultiplier = (int) $_GET["max-multiplier"]; 
  
  //generate the table
  echo "\n<table>"; 
  $multiplicand = (($maxMultiplicand - $minMultiplicand) + 1);
//  echo "multiplicand: $multiplicand<br>";
  $multiplier = (($maxMultiplier - $minMultiplier) + 1);
//  echo "multiplier: $multiplier<br><br>";
  generateHeader($minMultiplier,$multiplier);
  generateBody($minMultiplier,$minMultiplicand,$multiplier,$multiplicand);
  echo "\n</table>"; 
}

echo "\n</body>";
echo "\n</html>";
?>
