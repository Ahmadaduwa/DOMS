<?php

$servername = "db";
$username = 'MYSQL_USER';
$password = 'MYSQL_PASSWORD';
$dbname = 'MYSQL_DATABASE';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
