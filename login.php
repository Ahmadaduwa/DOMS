<?php
session_start();

// Database connection
require('./php_code/dbconnect.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve and sanitize form input
$username = $_POST['username'];
$password = $_POST['password'];

// Query the database for user
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    
    // Verify password (Assuming passwords are hashed in the database)
    if ($password === $user['password']) {  // Use password_verify() if passwords are hashed
        $_SESSION['name'] = $user['name'];
        $_SESSION['number'] = $user['number'];
        
        // Redirect based on 'number' field
        if ($user['number'] > 7) {
            header("Location: ./user/home.php");
        } elseif ($user['number'] >= 2 && $user['number'] <= 7) {
            header("Location: ./sub/home.php");
        } elseif ($user['number'] == 1) {
            header("Location: ./ohm/home.php");
        } elseif ($user['number'] == 0) {
            header("Location: ./admin/admin_main.php");
        }
        exit;
    } else {
        echo "Invalid password.";
    }
} else {
    echo "Invalid username.";
}

$stmt->close();
$conn->close();
?>
