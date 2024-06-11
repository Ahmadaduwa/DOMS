<?php
require('dbconnect.php');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id'])) {
    $documentId = $data['id'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Retrieve the level value
        $sql_get_level = "SELECT level FROM documents WHERE id = ?";
        $stmt_get_level = $conn->prepare($sql_get_level);
        $stmt_get_level->bind_param('i', $documentId);
        $stmt_get_level->execute();
        $stmt_get_level->bind_result($level);
        $stmt_get_level->fetch();
        $stmt_get_level->close();

        if ($level === null) {
            throw new Exception("Level not found for document ID: $documentId");
        }

        // Update the returned field
        $sql_update_returned = "UPDATE documents SET returned = 0 WHERE id = ?";
        $stmt_update_returned = $conn->prepare($sql_update_returned);
        $stmt_update_returned->bind_param('i', $documentId);
        if (!$stmt_update_returned->execute()) {
            throw new Exception("Error updating returned field: " . $stmt_update_returned->error);
        }
        $stmt_update_returned->close();

        // Update the at_who field with the level value
        $sql_update_at_who = "UPDATE documents SET at_who = ? WHERE id = ?";
        $stmt_update_at_who = $conn->prepare($sql_update_at_who);
        $stmt_update_at_who->bind_param('si', $level, $documentId);
        if (!$stmt_update_at_who->execute()) {
            throw new Exception("Error updating at_who field: " . $stmt_update_at_who->error);
        }
        $stmt_update_at_who->close();

        // Commit transaction
        $conn->commit();

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        // Rollback transaction
        $conn->rollback();

        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }

} else {
    echo json_encode(['success' => false, 'error' => 'Invalid document ID']);
}

$conn->close();
?>