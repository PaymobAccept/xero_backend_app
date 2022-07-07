<?php
$servername = "localhost";
$username = "wldhzlxq_mobbins";
$password = "mobbins1789";
$db = "wldhzlxq_ilease";

// Create connection
$conn = new mysqli($servername, $username, $password,$db);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
//echo "Connected successfully";
?>