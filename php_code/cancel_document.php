<?php
require('dbconnect.php');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id'])) {
    $documentId = $data['id'];
    $sql = "UPDATE documents SET returned = 2 WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $documentId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid document ID']);
}

$conn->close();
?>
