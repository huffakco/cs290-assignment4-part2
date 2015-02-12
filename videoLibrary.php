<?php
include 'errReport.php';
// Interact with the database
// get new requests

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

// function to get all unique categories from the database
// returns an array of categories
function getDistinctCategories($category) {
  // get list of categories from database
  if ($videos = $mysqli->query("SELECT DISTINCT category FROM videoLibrary")) {
    echo "Query failed: (" . $stmt->errno . ") " . $stmt->error;
    return(false);
  }
 
  $index = 0;
  while ($cat = $videos->fetch()) {
    $category[$index] = $cat;
    $index++;
  }  
  
  return($category);
}



// Get input variables
function getName() {
  if ((isset($_REQUEST["name"]))) {
    $name = $_REQUEST["name"];
  } else {
    $name = "invalid";
  }
}

function getCat() {    
  if ((isset($_REQUEST["category"]))) {
    $category = $_REQUEST["category"];
  } else {
    $category = "unassigned";
  }
  return ($category);
}

function getLength() {
  if ((isset($_REQUEST["length"]))) {
    $length = parseInt($_REQUEST["length"]);
  } else {
    $length = 0;
  }
  return ($length);
}

function getRented() {
  if ((isset($_REQUEST["rented"]))) {
    if ($_REQUEST["rented"] === 'TRUE') {
       return(TRUE);
    }
  }
  return(FALSE);
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

if (!(isset($_REQUEST["rtype"])) {
  echo "SOMETHING WENT TERRIBLY WRONG! Request to database not valid!";
}
else {
  $req = $_REQUEST["rtype"];
  switch $req
  {
    case('checkin')  
      // get name from request change rented to true
      $name = getName();
      $rented = FALSE;
      toggleRented($name, $rented);
      break;
    case ('checkout'):
      // get name from request, change rented to false
      $name = getName();
      $rented = TRUE;
      toggleRented($name, $rented);
      break;
    case ('getNames'):
      // get the list of video names, return array of names
      $name = getName();
      $result = checkNameUnique($name);
      // return $result to the requestor
      
      break;
    case ('insert'):
      // insert data provided into database
      $name = getName();
      $category = getCat();
      $length = getLength();
      $rented = getRented();
      $result = insertVideoData($name, $category, $length, $rented);
      // return $result to the requestor
      
      break;
    case ('deleteRow'):
      // get name from request, delete the row
      $name = getName();
      $result = deleteRow($name);
      // return $result to the requestor
      break;
    case ('deleteAll'):
      // delete all the rows in the database
      $result = deleteAllRows($name);
      // return $result to the requestor

      break;
    case ('getCategories'):
      // get the list of distinct categories, return array of categories
      $category = getCat();
      $result = getDistinctCategories($category);
      // stringify the array
      
      // return $result to the requestor (should be array of categories)

      break;
    case ('getVideoList'):
      // get categories
      $category = getCat();
      $result = getVideoListByCategory($category);
      // return $result to the requestor (should be array of objects)
      
    default:
      echo "<br>SOMETHING WENT TERRIBLY WRONG, unknown request<bre>";
  }
}

?>