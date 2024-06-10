<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['number'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Include database connection
require('dbconnect.php');

// Check connection
if ($conn->connect_error) {
    $response = array('status' => 'error', 'message' => "Connection failed: " . $conn->connect_error);
    echo json_encode($response);
    exit();
}

// Extract form data
$id = $_POST['id'];
$title = $_POST['title'];
$academic_year = $_POST['academic_year'];
$term = $_POST['term'];
$description = $_POST['description'];
$capacity = $_POST['capacity'];
$responsible = $_POST['responsible'];
$phone = $_POST['phone'];

// Update document details in the database
$stmt = $conn->prepare("UPDATE documents SET title=?, academic_year=?, term=?, description=?, capacity=?, responsible=?, phone=? WHERE id=?");
$stmt->bind_param("ssssssss", $title, $academic_year, $term, $description, $capacity, $responsible, $phone, $id);

if ($stmt->execute()) {
    // Delete old files associated with this document
    $stmt_select_files = $conn->prepare("SELECT file_path FROM files WHERE document_id = ?");
    $stmt_select_files->bind_param("i", $id);
    $stmt_select_files->execute();
    $stmt_select_files->store_result();
    $stmt_select_files->bind_result($file_path);

    while ($stmt_select_files->fetch()) {
        // Delete the file
        unlink($file_path);
    }

    $stmt_select_files->close();

    // Delete old file records from the database
    $stmt_delete_files = $conn->prepare("DELETE FROM files WHERE document_id = ?");
    $stmt_delete_files->bind_param("i", $id);
    $stmt_delete_files->execute();
    $stmt_delete_files->close();

    // Handle file uploads if any
    $messages = [];
    $file_paths = [];
    $target_dir = "./../uploads/";

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    if (!empty($_FILES['files']['name'][0])) {
        foreach ($_FILES['files']['name'] as $key => $name) {
            $fileType = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            $target_file = $target_dir . basename($name);

            // Check if file already exists
            if (file_exists($target_file)) {
                $filename = pathinfo($name, PATHINFO_FILENAME);
                $extension = pathinfo($name, PATHINFO_EXTENSION);
                $counter = 1;

                // Loop until a unique filename is found
                while (file_exists($target_dir . $filename . '_' . $counter . '.' . $extension)) {
                    $counter++;
                }

                // Assign unique filename
                $target_file = $target_dir . $filename . '_' . $counter . '.' . $extension;
            }

            // Check file size
            if ($_FILES["files"]["size"][$key] > 5000000) {
                $messages[] = "Sorry, your file $name is too large.";
                continue; // Skip this file
            }

            // Allow certain file formats
            if (!in_array($fileType, ["pdf", "doc", "docx", "jpg", "png"])) {
                $messages[] = "Sorry, only PDF, DOC, DOCX, JPG & PNG files are allowed for $name.";
                continue; // Skip this file
            }

            // Move uploaded file
            if (move_uploaded_file($_FILES["files"]["tmp_name"][$key], $target_file)) {
                $file_paths[] = $target_file;
            } else {
                $messages[] = "Sorry, there was an error uploading your file $name.";
            }
        }

        // Insert new file paths into the database
        foreach ($file_paths as $file_path) {
            $stmt_files = $conn->prepare("INSERT INTO files (document_id, file_path) VALUES (?, ?)");
            $stmt_files->bind_param("is", $id, $file_path);
            $stmt_files->execute();
            $stmt_files->close();
        }
    }

    $response = array('status' => 'success', 'message' => 'Document details updated successfully.', 'new_file_paths' => $file_paths, 'messages' => $messages);
} else {
    $response = array('status' => 'error', 'message' => 'Error updating document details: ' . $stmt->error);
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>
