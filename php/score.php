<?php
if(isset($_POST["score"]))

$finaleScore = filter_var($_POST["score"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);

include ('../../../config.php');
$sql_con = new mysqli($servername, $username, $password, $dbname);

if($stmt = $sql_con->prepare("INSERT INTO Quiz_Score (score) VALUES (?)")) {

   $stmt->bind_param("s", $finaleScore);
   $stmt->execute();
   $result = $stmt->get_result();
  
  echo "1";
  
  $stmt->close();
  $sql_con->close();
} else {
  echo "0";
}
  
?>