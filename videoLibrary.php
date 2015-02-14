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

 
toggleRentedVideo($mysqli,1,TRUE);

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

  
deleteVideo($mysqli,6);



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

// if (!(isset($_REQUEST["rtype"])) {
  // echo "SOMETHING WENT TERRIBLY WRONG! Request to database not valid!";
// }
// else {
  // $req = $_REQUEST["rtype"];
  // switch $req
  // {
    // case('checkin')  
      // // get name from request change rented to false
      // $name = getName();
      // $rented = FALSE;
      // toggleRented($name, $rented);
      // break;
    // case ('checkout'):
      // // get name from request, change rented to true
      // $name = getName();
      // $rented = TRUE;
      // toggleRented($name, $rented);
      // break;
    // case ('getNames'):
      // // get the list of video names, return array of names
      // $name = getName();
      // $result = checkNameUnique($name);
      // // return $result to the requestor
      
      // break;
    // case ('insert'):
      // // insert data provided into database
      // $name = getName();
      // $category = getCat();
      // $length = getLength();
      // $rented = getRented();
      // $result = insertVideoData($name, $category, $length, $rented);
      // // return $result to the requestor
      
      // break;
    // case ('deleteRow'):
      // // get name from request, delete the row
      // $name = getName();
      // $result = deleteRow($name);
      // // return $result to the requestor
      // break;
    // case ('deleteAll'):
      // // delete all the rows in the database
      // $result = deleteAllRows($name);
      // // return $result to the requestor

      // break;
    // case ('getCategories'):
      // // get the list of distinct categories, return array of categories
      // $category = getCat();
      // $result = getDistinctCategories($category);
      // // stringify the array
      
      // // return $result to the requestor (should be array of categories)

      // break;
    // case ('getVideoList'):
      // // get categories
      // $category = getCat();
      // $result = getVideoListByCategory($category);
      // // return $result to the requestor (should be array of objects)
      
    // default:
      // echo "<br>SOMETHING WENT TERRIBLY WRONG, unknown request<bre>";
  // }
// }

?>