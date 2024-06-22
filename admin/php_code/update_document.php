<?php
// Connect to database (replace with your database credentials)
require("./../../php_code/dbconnect.php");

// Function to sanitize and validate input
function sanitize_input($input)
{
    // Remove illegal characters
    $input = filter_var($input, FILTER_SANITIZE_STRING);
    return $input;
}

// Check if POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $documentId = sanitize_input($_POST['documentId']);
    $projectDropdown = sanitize_input($_POST['projectDropdown']);

    // Update the files table
    $sql = "UPDATE files SET document_id = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $projectDropdown, $documentId);

    if ($stmt->execute()) {
        $response = array("status" => "success");
    } else {
        $response = array("status" => "error", "message" => "Unable to update document.");
    }

    $stmt->close();
    echo json_encode($response);
}

$conn->close();
?>
