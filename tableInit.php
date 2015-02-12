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
include 'errReport.php';

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
  echo "<br>Connected successfully!<br>";
  echo json_encode($mysqli);
  echo "<br>";
  return($mysqli);
  
}

function disconnectVideoLibrary($stmt) {
 
}

$mysqli = connectVideoLibrary($myPassword);


//Reference:
// CS290 video
if ($mysqli) {
  if ($mysqli->query("CREATE TABLE  videoLibrary(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    category VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci,
    length INT UNSIGNED,
    rented BOOL NOT NULL DEFAULT FALSE,
    UNIQUE (
      name)
    )") === TRUE) {
    echo "<br>Table Successfully created!<br>";
  };
  
  $name = 'Veggie Tales';
  // $category = 'Family';
  // $length = 90;
  // $rented = FALSE;
  
  // need to generate a unique id!
 $mysqli->query("INSERT INTO videoLibrary(id,name) VALUES
   (1,'Veggie Tales 3')");
  // prototype an example insert
  // if ($mysqli->query("INSERT INTO videoLibrary(name,category,length,rented) VALUES
    // ('$name','$category','$length','$rented')") === TRUE {
    // echo "<br>Rows inserted!<br>";
  // }
//  if ($mysqli->query("INSERT INTO videoLibrary(name,category,length,rented) VALUES
//    ('Veggie Tales','Family',90,FALSE),
//    ('Muppets','Family',90,FALSE)") === TRUE {
//    echo "<br>Rows inserted!<br>";
//  }

  // add multiple parameters to this function
// Setup the prepare statement for inserting a properly formatted video
function insertVideoData($name, $category, $length, $rented) {
  // prepare statement
  //Reference: 
  // /PHP%20%20Prepared%20Statements%20-%20Manual.html
  /* Prepared statement, stage 1: prepare */
  if (!($stmt = $mysqli->prepare("INSERT INTO videoLibrary(name,category,length,rented) VALUES (?,?,?,?)"))) {
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

  
  
 echo "mysqli is true";
}
?>
