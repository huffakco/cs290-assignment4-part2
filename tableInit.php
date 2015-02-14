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

echo "Trying to connect...";

$mysqli = connectVideoLibrary($myPassword);
echo "<br>Error code: ";
echo $mysqli->errno;
echo "<br>";

//Reference:
// CS290 video + query from creating table manually in onid
// if ($mysqli) {
  // if ($mysqli->query("CREATE TABLE  videoLibrary(
    // id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    // name VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    // category VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci,
    // length INT UNSIGNED,
    // rented BOOL NOT NULL DEFAULT FALSE,
    // UNIQUE (
      // name)
    // )") === TRUE) {
    // echo "<br>Table Successfully created!<br>";
  // };
  

  /****************  Build test database interactions too move later **************/
  
  // Build video object
  // Reference:
  // http://php.net/manual/en/language.types.object.php
class VideoObject
{
  public $id;
  public $name;
  public $category;
  public $length;
  public $rented;
  

  //params is an array of key->value pairs
  public function _constructVideoObject($param) {
    $this->id = $param[id];
    $this->name = $param[name];
    $this->category = $param[category];
    $this->length = $param[length];
    $this->rented = $param[rented];
  }

}
  
  // $video1 = array(
    // name => 'Veggie Tales4';
    // );
  
  //$name = 'Veggie Tales';
  // $category = 'Family';
  // $length = 90;
  // $rented = FALSE;
  
  // need to generate a unique id!
 //$mysqli->query("INSERT INTO videoLibrary(id,name) VALUES
   // (1,'Veggie Tales 3')");
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

 deleteAllVideoData($mysqli);
  
 // Copied query from Brian Lamere OSU CS290 Canvas Discussion Board
 function deleteAllVideoData($mysqli) {
    echo "<br>Trying to delete all rows...<br>";
    $sql = "DELETE FROM videoLibrary WHERE 1";
    if ($mysqli->query($sql)) {
      echo "success";
    }
    else {
      echo "Delete failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
}

 
 insertVideoData($mysqli, 1, 'Veggie Tales 1', 'Family', 90, FALSE);
 insertVideoData($mysqli, 2, 'Veggie Tales 2', 'Family', 90, TRUE);
 insertVideoData($mysqli, 3, 'Veggie Tales 3', 'Comedy', 90, FALSE);
 insertVideoData($mysqli, 6, 'Veggie Tales 6', 'Family', 90, TRUE);

function insertVideoData($mysqli, $id, $name, $category, $length, $rented) {
  
  // prepare statement
  //Reference: 
  // /PHP%20%20Prepared%20Statements%20-%20Manual.html
  /* Prepared statement, stage 1: prepare */

  if (!($stmt = $mysqli->prepare("INSERT INTO videoLibrary(id, name,category,length,rented) VALUES (?,?,?,?,?)"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }
  
  /* Prepared statement, stage 2: bind and execute */
  if (!$stmt->bind_param("issii", $id,$name, $category, $length, $rented)) {
    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
  }

  if (!$stmt->execute()) {
      echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
  }
  
  // explicit close of prepared statement
  $stmt->close();
}

toggleRentedVideo($mysqli,1,TRUE);

function toggleRentedVideo($mysqli, $id, $rented) {
    echo "<br>Trying to update rented...<br>";

    // SQL syntax:
    // UPDATE table_name
    // SET column1=value1,column2=value2,...
    // WHERE some_column=some_value;
    
    // don't allow wierd values
    if ($rented)
      $rented = 1;
    else
      $rented = 0;
    
    $sql = "UPDATE videoLibrary SET rented=".$rented." WHERE id=".$id;
    if ($mysqli->query($sql)) {
      echo "success";
    }
    else {
      echo "Delete failed: (" . $mysqli->errno . ") " . $mysqli->error;
    } 
}



// Reference:
// http://stackoverflow.com/questions/7604893/sql-select-row-from-table-where-id-maxid
// SQL Syntax:
//SELECT row 
//FROM table 
//WHERE id=(
//    SELECT max(id) FROM table
//)
// SELECT * FROM table WHERE id = (SELECT MAX(id) FROM table);
echo "<br>ID: ".getNextId($mysqli);
function getNextId($mysqli) {
  // Note this works because id is a primary key
  $sql = "SELECT id FROM videoLibrary WHERE id=(SELECT max(id) FROM videoLibrary)";

  // prepare statement
  //Reference: 
  // /PHP%20%20Prepared%20Statements%20-%20Manual.html
  /* Prepared statement, stage 1: prepare */
  if ($stmt = $mysqli->prepare($sql)) {
    echo "success";
  }
  
  /* Prepared statement, stage 2: bind and execute */
  // No parameters to bind

  if (!$stmt->execute()) {
      echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
  } 
  
  // bind result
  if ($stmt->bind_result($id)) {
    echo "success";
  } 
  
  // get result
  if (!$stmt->fetch()) {
      echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
  } 
 
  echo "<br>ID: ".$id;
  echo "<br>Trying to print stmt...";
  echo json_encode($stmt);
  echo "<br>";  
  
  // explicit close of prepared statement
  $stmt->close();
  $id++;
  return ($id);
}
  
  
$categories = getDistinctCategories($mysqli);
for ($ii = 0; $ii < sizeof($categories); $ii++) {
  echo "<br>List category: ".$categories[$ii];
}

function getDistinctCategories($mysqli) {
  // Get the distinct elements from video Library
  // prepare statement
  //Reference: 
  // /PHP%20%20Prepared%20Statements%20-%20Manual.html
  /* Prepared statement, stage 1: prepare */
  $sql = "SELECT DISTINCT category FROM videoLibrary";
  if ($stmt = $mysqli->prepare($sql)) {
    echo "success";
  }
  
  /* Prepared statement, stage 2: bind and execute */
  
  if (!$stmt->execute()) {
      echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
  } 
  
  // bind result
  if ($stmt->bind_result($result)) {
    echo "success";
  } 
 
  // get result
  $idx = 0;
  while ($stmt->fetch()) {
        $array[$idx] = $result;
        echo "<br>Result:" . $result;
        $idx++;
  }
 
  // explicit close of prepared statement
  $stmt->close();
  return ($array);
}
  

$names = getNames($mysqli);
for ($ii = 0; $ii < sizeof($names); $ii++) {
  echo "<br>List names: ".$names[$ii];
}

function getNames($mysqli) {
  // Get the name elements from video Library
  // prepare statement
  //Reference: 
  // /PHP%20%20Prepared%20Statements%20-%20Manual.html
  /* Prepared statement, stage 1: prepare */
  $sql = "SELECT name FROM videoLibrary";
  if ($stmt = $mysqli->prepare($sql)) {
    echo "success";
  }
  
  /* Prepared statement, stage 2: bind and execute */
  
  if (!$stmt->execute()) {
      echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
  } 
  
  // bind result
  if ($stmt->bind_result($result)) {
    echo "success";
  } 
 
  // get result
  $idx = 0;
  while ($stmt->fetch()) {
        $array[$idx] = $result;
        echo "<br>Result:" . $result;
        $idx++;
  }
 
  // explicit close of prepared statement
  $stmt->close();
  return ($array);
}
  


$videos = getAllVideos($mysqli);
for ($ii = 0; $ii < sizeof($videos); $ii++) {
  echo "<br>List video names: ".$videos[$ii]['name'];
}

function getAllVideos($mysqli) {
  // Get the name elements from video Library
  // prepare statement
  //Reference: 
  // /PHP%20%20Prepared%20Statements%20-%20Manual.html
  /* Prepared statement, stage 1: prepare */
  $sql = "SELECT id, name, category, length, rented FROM videoLibrary";
  if ($stmt = $mysqli->prepare($sql)) {
    echo "success";
  }
  
  /* Prepared statement, stage 2: bind and execute */
  
  if (!$stmt->execute()) {
      echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
  } 
  
  // bind result
  if ($stmt->bind_result($id,$name,$category,$length,$rented)) {
    echo "success";
  } 
 
  // get result
  $idx = 0;
  while ($stmt->fetch()) {
        $tmpObj[$idx] = array('id'=>$id,'name'=>$name,'category'=>$category,'length'=>$length,'rented'=>$rented);
        $nameArr[$idx] = $name;
        echo "<br>Result:" . $name;
        $idx++;
  }
 
  // explicit close of prepared statement
  $stmt->close();
  return ($tmpObj);
}
  
deleteVideo($mysqli,6);
  
 // Copied query from Brian Lamere OSU CS290 Canvas Discussion Board
 function deleteVideo($mysqli,$id) {
    echo "<br>Delete id = $id<br>";
    $sql = "DELETE FROM videoLibrary WHERE id=$id";
    if ($mysqli->query($sql)) {
      echo "success";
    }
    else {
      echo "Delete failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
}

?>
