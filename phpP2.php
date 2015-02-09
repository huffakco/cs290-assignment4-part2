<?php
echo "<!DOCTYPE html>";
echo "<html>";
echo "\n<head>";
echo "<meta charset='utf-8'>";
echo "\n<title>Video Log</title>";
echo "\n<style type=\"text/css\">\ntable, td, th {\nborder: 1px solid #777;\n}";
echo "\n</style>";
echo "\n</head>";
echo "\n<body>";
// Thanks to Canvas discussion and OSU 290 video
error_reporting(E_ALL);
ini_set('display_errors', 1);

// function to check for data not null and numeric
function chkValid($val, $str)
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
          return($str.' must be an positive.');
        }
      }
    }
    return("");
};

// function to check for data not null or empty
function chkNotEmpty($val, $str)
{
    if (empty($val) && ($val != '0')) {
        return ('Missing parameter '.$str.'.');
    }
    return("");
};


function generateHeader($minVal,$val) {
  echo "\n<thead>";
  echo "\n<tr>";

  // First empty cell
  echo "\n<th></th>";

  // Define header elements
  for ($i = $minVal; $i < ($minVal + $val); $i++)
  {
    echo "\n<th>";
    echo $i;
    echo "</th>";
  }
  echo "\n</tr>";
  echo "\n</thead>";
};


function generateBody($minMt, $minMy, $valMt, $valMy) {
  echo "\n<tbody>";
 
  //creating all cells
  for ($i = $minMy; $i < ($minMy + $valMy) ; $i++) {
    //creates a table row
    echo "\n<tr>";
    //create first element in row
    echo '<th>'.$i.'</th>';
    for ($j = $minMt; $j < ($minMt + $valMt); $j++) {
      echo '<td>'.($i * $j).'</td>';
      }
    // end row
    echo "\n</tr>";
  }
  echo "\n</tbody>";
};

// Get input variables
function checkInputs() {
  $result = true;
  
  if (!(isset($_GET["min-multiplicand"]))) {
    echo "<br>Missing parameter min-multiplicand.<br>";
    $result = false;
  } else {
    $chkValue = chkValid($_GET["min-multiplicand"],"min-multiplicand");
    if (!(empty($chkValue) )) {
      echo "<br>$chkValue<br>"; 
      $result = false;
    }
  }
  
  if (!(isset($_GET["max-multiplicand"]))) {
    echo "<br>Missing parameter max-multiplicand.<br>";
    $result = false;
  } else {
    $chkValue = chkValid($_GET["max-multiplicand"],"max-multiplicand");
    if (!(empty($chkValue) )){
      echo "<br>$chkValue<br>"; 
      $result = false;
    }
  }
  
  if (!(isset($_GET["min-multiplier"]))) {
    echo "<br>Missing parameter min-multiplier.<br>";
    $result = false;
  } else {
    $chkValue = chkValid($_GET["min-multiplier"],"min-multiplier");
    if (!(empty($chkValue) )){
      echo "<br>$chkValue<br>"; 
      $result = false;
    }
  }
  
  
  if (!(isset($_GET["max-multiplier"]))) {
    echo "<br>Missing parameter max-multiplier.<br>";
    $result = false;
  } else {
    $chkValue = chkValid($_GET["max-multiplier"],"max-multiplier");
    if (!(empty($chkValue) )){
      echo "<br>$chkValue<br>"; 
      $result = false;
    }
  }
  
  if ($result) {
    //check for max > min
    if ($_GET["max-multiplicand"] < $_GET["min-multiplicand"]) {
      echo "<br>Minimum multiplicand larger than maximum<br>";
      $result = false;
    }
    if ($_GET["max-multiplier"] < $_GET["min-multiplier"]) {
      echo "<br>Minimum multiplier larger than maximum<br>";
      $result = false;
    }
  }
  
  return($result);
}

if (checkInputs()) {
  // get inputs
  $minMultiplicand = (int) $_GET["min-multiplicand"];
  $maxMultiplicand = (int) $_GET["max-multiplicand"];
  $minMultiplier = (int) $_GET["min-multiplier"]; 
  $maxMultiplier = (int) $_GET["max-multiplier"]; 

//  echo "minMultiplicand: $minMultiplicand<br>";
//  echo "maxMultiplicand: $maxMultiplicand<br>";
//  echo "minMultiplier: $minMultiplier<br>";
//  echo "maxMultiplier: $maxMultiplier<br>";
  
  //generate the table
  echo "\n<table>"; 
  $multiplicand = (($maxMultiplicand - $minMultiplicand) + 1);
//  echo "multiplicand: $multiplicand<br>";
  $multiplier = (($maxMultiplier - $minMultiplier) + 1);
//  echo "multiplier: $multiplier<br><br>";
  generateHeader($minMultiplier,$multiplier);
  generateBody($minMultiplier,$minMultiplicand,$multiplier,$multiplicand);
  echo "\n</table>"; 
}

echo "\n</body>";
echo "\n</html>";
?>
