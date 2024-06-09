<?php
session_start();
require('dbconnect.php');

header('Content-Type: application/json');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['documentId']) && isset($_POST['userNumber']) && !empty($_POST['documentId']) && !empty($_POST['userNumber'])) {
    $documentId = intval($_POST['documentId']);
    $userNumber = intval($_POST['userNumber']);

    $sql = "UPDATE documents SET returned = 1, at_who = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $userNumber, $documentId); // Change parameter order

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'Invalid documentId or userNumber']);
}

$conn->close();
?>
