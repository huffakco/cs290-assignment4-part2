<?php
// This file opens a connection to the database and then
// checks if the table exists, if it doesn't 
// the table gets created.
  
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


// Query generated from graphical interface for mySQL
// CREATE TABLE  `huffakco-db`.`videoLibrary` (
// `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
// `name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
// `category` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
// `length` INT UNSIGNED NULL ,
// `rented` BOOL NOT NULL DEFAULTFALSE,
//UNIQUE (
// `name`
//)
//) ENGINE = MYISAM

// include configuration file here
include 'configuration.php';
//include 'errReport.php';

function connectVideoLibrary($myPassword) {

//Connect to database
  //Reference: 
  // /PHP%20%20Prepared%20Statements%20-%20Manual.html
  $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "huffakco-db", $myPassword, "huffakco-db");
 
  if ($mysqli->connect_errno) {
      echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
      echo "<br>";
      echo json_encode($mysqli);
      echo "<br>";
      return(false);
  }
  //echo "<br>Connected successfully!<br>";
  //echo json_encode($mysqli);
  //echo "<br>";
  return($mysqli);
  
}

function disconnectVideoLibrary($stmt) {
 
}

//Reference:
// CS290 video + query from creating table manually in onid
function createTable($mysqli) {
  if ($mysqli->query("CREATE TABLE  videoLibrary(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    category VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci,
    length INT UNSIGNED,
    rented BOOL NOT NULL DEFAULT FALSE,
    UNIQUE (
      name)
    )")) {
      //echo "<br>Table Successfully created!<br>";
    }
  else {
    //echo "table create error: " . $mysqli->errno;
    //echo "<br>";        
  }
}

?>
