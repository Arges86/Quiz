<?php
include ('../../../config.php');

  // Create connection
$sql_con = new mysqli($servername, $username, $password, $dbname);

// if ($sql = "SELECT score, date FROM Quiz_Score ORDER BY score ASC;") {
if ($sql = "SELECT COUNT(score) AS y, score AS x FROM myDB.Quiz_Score GROUP BY score ORDER BY y ASC") {
$result = mysqli_fetch_all($sql_con->query($sql), MYSQLI_ASSOC);
//   print_r($result);
  $results = json_encode($result);
  print_r($results);
  
$sql_con->close();
}
?>