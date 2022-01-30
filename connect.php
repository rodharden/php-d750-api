<?php
$servername = "localhost";
$username = "root";
$password = "maria123";
$dbname = "drink750ml-main";

// Create connection
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }
echo "Connected successfully";

$sql = "SELECT Id, Nome FROM test";
$result = mysqli_query($conn, $sql);
 if (mysqli_num_rows($result) > 0) {
     // output data of each row
     while ($row = mysqli_fetch_assoc($result)) {
         echo "id: " . $row["Id"] . " - Name: " . $row["Nome"] . "<br>";
     }
 } else {
     echo "0 results";
 }
 $conn->close();
