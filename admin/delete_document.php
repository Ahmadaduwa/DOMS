<?php
// Include database connection
require_once("./../php_code/dbconnect.php");

// Check if ID parameter is sent
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Prepare statement to get file path
    $stmt = $conn->prepare("SELECT file_path FROM files WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($file_path);
    $stmt->fetch();
    $stmt->close();

    // Delete file from server
    if (unlink($file_path)) { // Attempt to delete file
        // Proceed to delete file entry from database
        $stmt_delete = $conn->prepare("DELETE FROM files WHERE id = ?");
        $stmt_delete->bind_param("i", $id);

        if ($stmt_delete->execute()) {
            // Return success response
            $response = [
                'status' => 'success',
                'message' => 'Document deleted successfully.'
            ];
            echo json_encode($response);
        } else {
            // Return error response
            $response = [
                'status' => 'error',
                'message' => 'Failed to delete document from database.'
            ];
            echo json_encode($response);
        }

        // Close delete statement
        $stmt_delete->close();
    } else {
        // Return error response if unable to delete file
        $response = [
            'status' => 'error',
            'message' => 'Failed to delete document file.'
        ];
        echo json_encode($response);
    }

    // Close database connection
    $conn->close();
} else {
    // Return error if ID parameter is missing
    $response = [
        'status' => 'error',
        'message' => 'ID parameter is missing.'
    ];
    echo json_encode($response);
}
?>
