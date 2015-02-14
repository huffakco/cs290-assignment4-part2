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


// include configuration file here
include 'errReport.php';

  
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
