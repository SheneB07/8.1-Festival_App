<?php
$servername = "localhost";
$username = "u240660_ufestival"; 
$password = "kMMbQ7F5B3zZ4qNByQ5f";
$dbname = "u240660_ufestival";  // Add a semicolon here

// $servername = "localhost";
// $username = "root"; 
// $password = "";
// $dbname = "8.1_ufestival";  // Add a semicolon here

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);  // Use $dbname
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //echo "Connected successfully";
} catch(PDOException $e) {
  //echo "Connection failed: " . $e->getMessage();
}
?>
