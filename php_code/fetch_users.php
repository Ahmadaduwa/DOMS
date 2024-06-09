<?php
session_start();
require('dbconnect.php');

header('Content-Type: application/json');

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['documentId']) && !empty($_POST['documentId'])) {
    $documentId = intval($_POST['documentId']);

    if (isset($_SESSION['number'])) {
        $userNumber = $_SESSION['number'];

        $sql = "SELECT number, name FROM users WHERE number > ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $userNumber);
        $stmt->execute();
        $result = $stmt->get_result();

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        if (empty($users)) {
            echo json_encode(['error' => 'No users found with higher number', 'session' => $_SESSION, 'post' => $_POST]);
        } else {
            echo json_encode($users);
        }

        $stmt->close();
    } else {
        echo json_encode(['error' => 'Session number is not set', 'session' => $_SESSION, 'post' => $_POST]);
    }
} else {
    echo json_encode(['error' => 'Invalid documentId']);
}

$conn->close();
?>
