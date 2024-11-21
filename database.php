<?php
$host = 'localhost'; 
$user = 'root';      
$password = 'anup123@';     
$database = 'crud'; 

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
