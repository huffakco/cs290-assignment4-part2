<?php
include 'errReport.php';
include 'tableInit.php';
include 'dbFunctions.php';

/*********  Interact with the database ***********/


echo "Trying to connect...";

$mysqli = connectVideoLibrary($myPassword);
echo "<br>Error code: ";
echo $mysqli->errno;
echo "<br>";
if ($mysqli) {
  createTable($mysqli);
}


 deleteAllVideoData($mysqli);

 
 insertVideoData($mysqli, 1, 'Veggie Tales 1', 'Family', 90, FALSE);
 insertVideoData($mysqli, 2, 'Veggie Tales 2', 'Family', 90, TRUE);
 insertVideoData($mysqli, 3, 'Veggie Tales 3', 'Comedy', 90, FALSE);
 insertVideoData($mysqli, 6, 'Veggie Tales 6', 'Family', 90, TRUE);

echo "<br>ID: ".getNextId($mysqli);

  
$categories = getDistinctCategories($mysqli);
for ($ii = 0; $ii < sizeof($categories); $ii++) {
  echo "<br>List category: ".$categories[$ii];
}
 
$names = getNames($mysqli);
for ($ii = 0; $ii < sizeof($names); $ii++) {
  echo "<br>List names: ".$names[$ii];
}


$videos = getAllVideos($mysqli);
for ($ii = 0; $ii < sizeof($videos); $ii++) {
  echo "<br>List video names: ".$videos[$ii]['name'];
}

  




/********* Get input variables *****************/
function getId() {
  if ((isset($_REQUEST["name"]))) {
    $name = $_REQUEST["name"];
  } else {
    $name = "invalid";
  }
}

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

//if (!(isset($_REQUEST["rtype"])) {
  // echo "SOMETHING WENT TERRIBLY WRONG! Request to database not valid!";
// }
// else {
  // $req = $_REQUEST["rtype"];
  $req = 'insert';
  $reqId = 1;
  


  switch ($req)
  {
    case('checkin'):
      // get name from request change rented to false
      //$reqId = getId();
      
      $result = toggleRentedVideo($mysqli, $reqId, FALSE);
      break;
    case ('checkout'):
      // get name from request, change rented to true
      // $reqId = getId();
      $result = toggleRentedVideo($mysqli, $reqId, TRUE);
      break;
    case ('getNames'):
      // get the list of video names, return array of names
      $names = getNames($mysqli);
      for ($ii = 0; $ii < sizeof($names); $ii++) {
          echo "<br>List names: ".$names[$ii];
      }
      break;
    case ('insert'):
      // // insert data provided into database
      // $name = getName();
      // $category = getCat();
      // $length = getLength();
      // $rented = getRented();
      $nextId = getNextId($mysqli);
      $newName = 'Veggie Tales '.$nextId;
      $result = insertVideoData($mysqli, $nextId, $newName, 'Family', 90, FALSE);
      // return $result to the requestor
      break;
    case ('deleteRow'):
      // get id from request, delete the row
      // $reqId = getId();
      $result = deleteVideo($mysqli,$reqId);
      break;
    case ('deleteAll'):
      // delete all the rows in the database
      $result = deleteAllVideoData($mysqli);
      break;
    case ('getCategories'):
      // // get the list of distinct categories, return array of categories
      $categories = getDistinctCategories($mysqli);
      for ($ii = 0; $ii < sizeof($categories); $ii++) {
        echo "<br>List category: ".$categories[$ii];
      }
      break;
    case ('getVideoList'):
      // get categories
      $videos = getAllVideos($mysqli);
      for ($ii = 0; $ii < sizeof($videos); $ii++) {
        echo "<br>List video names: ".$videos[$ii]['name'];
      }
      break;
    default:
       echo "<br>SOMETHING WENT TERRIBLY WRONG, unknown request<bre>";
  }

?>