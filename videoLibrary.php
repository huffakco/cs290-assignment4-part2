<?php
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




// Get input variables
function getInputs() {
  if ((isset($_REQUEST["name"]))) {
    $name = $_REQUEST["name"];
  } else {
    $name = "";
  }
    
  if ((isset($_REQUEST["category"]))) {
    $category = $_REQUEST["category"];
  } else {
    $category = "unassigned";
  }
  
  if ((isset($_REQUEST["length"]))) {
    $length = parseInt($_REQUEST["length"]);
  } else {
    $length = 0;
  }

  if ((isset($_REQUEST["rented"]))) {
    $rented = parseInt($_REQUEST["rented"]);
  } else {
    $rented = TRUE;
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

if (!(isset($_REQUEST["rtype"])) {
  echo "SOMETHING WENT TERRIBLY WRONG! Request to database not valid!";
}
else {
  $req = $_REQUEST["rtype"];
  switch $req
  {
    case($req === 'checkin')  
    // get name from request change rented to true
    break;
    case ($req === 'checkout')
    // get name from request, change rented to false
    break;
    case ($req === 'insert')
    break;
    case ($req === 'deleteRow')
    break;
    case ($req === 'deleteAll')
    break;
    default
      echo "";
  }
  }

?>