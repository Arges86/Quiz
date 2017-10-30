<?php
include ('../../../config.php');
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed");
} 

$sql = "SELECT COUNT(id) FROM Quiz";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        print_r($row["COUNT(id)"]);
    }
} else {
    echo "0 results";
}
$conn->close();
?>