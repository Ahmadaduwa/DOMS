<?php
session_start();
require('dbconnect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $commentText = htmlspecialchars($_POST['commentText']);
    $documentId = intval($_POST['documentId']);
    $date = date('Y-m-d H:i:s'); // Get the current date and time

    try {
        $stmt = $conn->prepare("INSERT INTO comments (document_id, date, comment) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $documentId, $date, $commentText);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Comment saved successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error saving comment: ' . $stmt->error]);
        }

        $stmt->close();
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()]);
    }
    
    $conn->close();
}
?>