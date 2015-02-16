<?php
include 'errReport.php';
include 'tableInit.php';
include 'dbFunctions.php';

/*********  Interact with the database ***********/


//echo "Trying to connect...";

$mysqli = connectVideoLibrary($myPassword);
//echo "<br>Error code: ";
//echo $mysqli->errno;
//echo "<br>";
if ($mysqli) {
  createTable($mysqli);
}


if($_SERVER['REQUEST_METHOD'] === 'GET') {

  if (isset($_GET) && (sizeof($_GET) > 0)) {
    if (isset($_GET["insert"]) && ($_GET["insert"])) {
      $req = "insert";
      $nameR = $_GET["name"];
      $categoryR = $_GET["category"];
      $lengthR = $_GET["length"];
    }
    else {
      foreach ($_GET as $key => $value) {
        //echo "$key: $value<br>";
        //$getStr = "$getStr\"$key\":\"$value\",";
        $req = $key;
        //echo $req;
        $val = $value;
      }
    }
    
    switch ($req)
    {
      case('checkin'):
        // get name from request change rented to false
        //$reqId = getId();
        
        $result = toggleRentedVideo($mysqli, $val, FALSE);
        $getStr = "{\"Type\":\"GET\",\"result\":";
        $jsonStr = json_encode($result);
        echo "$getStr $jsonStr}";
        break;
      case ('checkout'):
        // get name from request, change rented to true
        // $reqId = getId();
        $result = toggleRentedVideo($mysqli, $val, TRUE);
        $getStr = "{\"Type\":\"GET\",\"result\":";
        $jsonStr = json_encode($result);
        echo "$getStr $jsonStr}";
        break;
      case ('getNames'):
        // get the list of video names, return array of names
        $names = getNames($mysqli);
        for ($ii = 0; $ii < sizeof($names); $ii++) {
            echo "<br>List names: ".$names[$ii];
        }
        break;
      case ('insert'):
        // insert data provided into database
        $nextId = getNextId($mysqli);
        //echo "insert params: ".$nextId;
        //echo $nameR;
        //echo $categoryR;
        //echo $lengthR;
        $result = insertVideoData($mysqli, $nextId, $nameR, $categoryR, $lengthR, FALSE);
        $getStr = "{\"Type\":\"GET\",\"result\":";
        $jsonStr = json_encode($result);
        echo "$getStr $jsonStr}";
        break;
      case ('deleteRow'):
        // get id from request, delete the row
        // $reqId = getId();
        $result = deleteVideo($mysqli,$val);
        $getStr = "{\"Type\":\"GET\",\"result\":";
        $jsonStr = json_encode($result);
        echo "$getStr $jsonStr}";
        break;
      case ('deleteAll'):
        // delete all the rows in the database
        $result = deleteAllVideoData($mysqli);
        $getStr = "{\"Type\":\"GET\",\"result\":";
        $jsonStr = json_encode($result);
        echo "$getStr $jsonStr}";
        break;
      case ('getCategories'):
        // // get the list of distinct categories, return array of categories
        $categories = getDistinctCategories($mysqli);
        $getStr = "{\"Type\":\"GET\",\"categories\":";
        if (!is_null($categories)) {
          for ($ii = 0; $ii < sizeof($categories); $ii++) {
            //echo "<br>List category: ".$categories[$ii];
          }
          $jsonStr = json_encode($categories);
          echo "$getStr $jsonStr}";  
          //echo "$jsonStr";  
        }
        else {
          echo "$getStr null}";          
          //echo "null";          
        }
        break;
      case ('getVideoList'):
        // get categories
        $videos = getAllVideos($mysqli,$val);
        $strArr = null;
        $getStr = "{\"Type\":\"GET\",\"videos\":";
        if (!is_null($videos)) {
          for ($ii = 0; $ii < sizeof($videos); $ii++) {
            //echo "<br>List video names: ".$videos[$ii]['name'];
            $strArr[$ii] = json_encode($videos[$ii]);
          }
          $jsonStr = json_encode($strArr);
          echo "$getStr $jsonStr}";
        }
        else {
          echo "$getStr null}";
        }
        break;
      default:
         echo "<br>SOMETHING WENT TERRIBLY WRONG, unknown request<br>";
         echo "request:".$req;
         echo "value".$val;
    }
 
  }
}





// Some tests
 // deleteAllVideoData($mysqli);

 
 // insertVideoData($mysqli, 1, 'Veggie Tales 1', 'Family', 90, FALSE);
 // insertVideoData($mysqli, 2, 'Veggie Tales 2', 'Family', 90, TRUE);
 // insertVideoData($mysqli, 3, 'Veggie Tales 3', 'Comedy', 90, FALSE);
 // insertVideoData($mysqli, 6, 'Veggie Tales 6', 'Family', 90, TRUE);

// echo "<br>ID: ".getNextId($mysqli);

  
// $categories = getDistinctCategories($mysqli);
// for ($ii = 0; $ii < sizeof($categories); $ii++) {
  // echo "<br>List category: ".$categories[$ii];
// }
 
// $names = getNames($mysqli);
// for ($ii = 0; $ii < sizeof($names); $ii++) {
  // echo "<br>List names: ".$names[$ii];
// }


// $videos = getAllVideos($mysqli);
// for ($ii = 0; $ii < sizeof($videos); $ii++) {
  // echo "<br>List video names: ".$videos[$ii]['name'];
// }


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
//  $req = 'insert';
//  $reqId = 1;
  



?>