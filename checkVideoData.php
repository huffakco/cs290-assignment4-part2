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
          return($str.' must be a positive integer.');
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


// Get input variables
function checkInputs() {
  $result = true;
  
  if (!(isset($_POST['name']))) {
    echo "<br>Missing data in name field.<br>";
    $result = false;
  } else {
    $chkvalue = chkNotEmpty($_POST['name'], 'name');
    if (!(empty($chkValue) )) {
      echo "<br>$chkValue<br>"; 
      $result = false;
    } else {
      if (chkPosInteger($name)) {
        echo '<br>'.$name.' is not unique in this database<br>';
      }
    }
  }
    
  if (!(isset($_POST['category']))) {
    echo "<br>Missing data in category field.<br>";
    $result = false;
  } else {
    $chkvalue = chkNotEmpty($_POST['category'], 'category');
    if (!(empty($chkValue) )) {
      echo "<br>$chkValue<br>"; 
      $result = false;
    }
  }
  
  if (!(isset($_POST['length']))) {
    echo "<br>Missing data in length field.<br>";
    $result = false;
  } else {
    $chkvalue = chkPosInteger($_POST['category'], 'category');
    if (!(empty($chkValue) )) {
      echo "<br>$chkValue<br>"; 
      $result = false;
    }
  }
}


// add multiple parameters to this function
// Setup the prepare statement for inserting a properly formatted video
function insertVideoData($name, $category, $length, $rented) {
  // prepare statement
  //Reference: 
  // /PHP%20%20Prepared%20Statements%20-%20Manual.html
  /* Prepared statement, stage 1: prepare */
  if (!($stmt = $mysqli->prepare("INSERT INTO videoLibrary(name) VALUES (?)"))) {
      echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }
  
  /* Prepared statement, stage 2: bind and execute */
  //  $id = 1; 
  if (!$stmt->bind_param("i", $name)) {
      echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
  }
  
  if (!$stmt->execute()) {
      echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
  }
  
  // explicit close of prepared statement
  $stmt->close();
}


if (checkInputs()) {
  // get inputs
  $name = $_POST['name'];
  $category = $_POST['category'];
  $length = parseInt($_POST['length']);
    
  // insert data into the table
  $rented = TRUE;
  insertVideoData($name, $category, $length, $rented);
  
  // redirect to table generate page

else {
  // provide link to the input data page
  
}

?>
