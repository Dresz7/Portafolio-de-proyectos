<?php
$servername = "localhost";
$username = "root";
$upassword = "DreszSeven123";

// Create connection
$conn = new mysqli($servername, $username, $upassword);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
// echo "Connected successfully";
$conn->select_db("cfe");
$conn->set_charset("utf8mb4");
?>