<?php
$host = "localhost";
$user = "root";
$pass = "1a3g5m+Manik"; // Use your MySQL root password if set
$db = "book_store";

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

