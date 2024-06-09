<?php
// Database connection
require('dbconnect.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the cardId is set in the POST request
if (isset($_POST['id'])) {
    // Get the id from the POST request
    $cardId = $_POST['id'];

    // Delete files associated with the document
    $sql_select_files = "SELECT file_path FROM files WHERE document_id = ?";
    $stmt_select_files = $conn->prepare($sql_select_files);
    $stmt_select_files->bind_param('i', $cardId);
    $stmt_select_files->execute();
    $result_files = $stmt_select_files->get_result();

    // Loop through the files and delete each one
    while ($row = $result_files->fetch_assoc()) {
        $file_path = $row['file_path'];
        if (file_exists($file_path)) {
            unlink($file_path); // Delete the file
        }
    }

    // Close prepared statement
    $stmt_select_files->close();

    // Delete files associated with the document from database
    $sql_delete_files = "DELETE FROM files WHERE document_id = ?";
    $stmt_delete_files = $conn->prepare($sql_delete_files);
    $stmt_delete_files->bind_param('i', $cardId);
    $stmt_delete_files->execute();
    $stmt_delete_files->close();

    // Delete comments associated with the document from database
    $sql_delete_files = "DELETE FROM comments WHERE document_id = ?";
    $stmt_delete_files = $conn->prepare($sql_delete_files);
    $stmt_delete_files->bind_param('i', $cardId);
    $stmt_delete_files->execute();
    $stmt_delete_files->close();

    // Delete the document itself from database
    $sql_delete_document = "DELETE FROM documents WHERE id = ?";
    $stmt_delete_document = $conn->prepare($sql_delete_document);
    $stmt_delete_document->bind_param('i', $cardId);
    $stmt_delete_document->execute();
    $stmt_delete_document->close();

    // Check if there are any remaining documents
    $sql_remaining_documents = "SELECT COUNT(*) AS num_documents FROM documents";
    $result_remaining_documents = $conn->query($sql_remaining_documents);
    $row_remaining_documents = $result_remaining_documents->fetch_assoc();
    $num_documents = $row_remaining_documents['num_documents'];

    if ($num_documents === 0) {
        echo "<div class='alert alert-warning' role='alert'>No documents found.</div>";
    } else {
        echo json_encode(array('status' => 'success', 'message' => 'Document and associated files deleted successfully.'));
    }
} else {
    echo json_encode(array('status' => 'error', 'message' => 'No document ID provided.'));
}

$conn->close();
?>
