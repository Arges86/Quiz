<?php

if(isset($_POST["postID"]))

//  $postID = "1" ;

$postID = filter_var($_POST["postID"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);

include ('../../../config.php');
$sql_con = new mysqli($servername, $username, $password, $dbname);

if($stmt = $sql_con->prepare("SELECT * FROM Quiz WHERE id = ?")) {

   $stmt->bind_param("s", $postID); 
   $stmt->execute(); 
   $result = $stmt->get_result();
  
  if ($result->num_rows > 0) {
   while ($myrow = $result->fetch_assoc()) {
     print_r(json_encode($myrow));
   }
  } else {
    echo "0";
  }
  $stmt->close();
  $sql_con->close();
}
  
?>