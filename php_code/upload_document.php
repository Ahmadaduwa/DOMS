<?php
session_start();
$target_dir = "./../uploads/";
$uploadOk = 1;
$allowed_types = ["pdf", "doc", "docx", "jpg", "png"];
$messages = [];
$file_paths = [];
$document_id = null; // Variable to store the ID of the newly added document

// Create directory if it doesn't exist
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true);
}

foreach ($_FILES['files']['name'] as $key => $name) {
    $fileType = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    $uploadOk = 1; // Reset uploadOk for each file

    // Check file size
    if ($_FILES["files"]["size"][$key] > 5000000) {
        $messages[] = "Sorry, your file $name is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (!in_array($fileType, $allowed_types)) {
        $messages[] = "Sorry, only PDF, DOC, DOCX, JPG & PNG files are allowed for $name.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        $messages[] = "Sorry, your file $name was not uploaded.";
    } else {
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

        if (move_uploaded_file($_FILES["files"]["tmp_name"][$key], $target_file)) {
            $file_paths[] = $target_file;
        } else {
            $messages[] = "Sorry, there was an error uploading your file $name.";
        }
    }
}

if ($uploadOk == 1 && !empty($file_paths)) {
    // Save the form data to the database
    $title = $_POST['title'];
    $date = date("Y-m-d H:i:s"); // Current date and time
    $academic_year = $_POST['academic_year'];
    $term = $_POST['term'];
    $description = $_POST['description'];
    $capacity = $_POST['capacity'];
    $responsible = $_POST['responsible'];
    $phone = $_POST['phone'];
    $owner = $_SESSION['number'];
    $at_who = $_SESSION['number'];
    $level = 7;
    $returned = 2;

    // Database connection
    require('dbconnect.php');

    // Check connection
    if ($conn->connect_error) {
        $messages[] = "Connection failed: " . $conn->connect_error;
    } else {
        $stmt = $conn->prepare("INSERT INTO documents (title, date, academic_year, term, description, capacity, responsible, phone, level, owner, at_who, returned) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssisiss", $title, $date, $academic_year, $term, $description, $capacity, $responsible, $phone, $level, $owner, $at_who, $returned);

        if ($stmt->execute()) {
            $document_id = $stmt->insert_id; // Store the ID of the newly added document
            $messages[] = "The files have been uploaded and form data saved.";

            // Insert file paths into the files table
            foreach ($file_paths as $file_path) {
                $stmt_files = $conn->prepare("INSERT INTO files (document_id, file_path) VALUES (?, ?)");
                $stmt_files->bind_param("is", $document_id, $file_path);
                if (!$stmt_files->execute()) {
                    $messages[] = "Error inserting file path: " . $stmt_files->error;
                }
                $stmt_files->close();
            }
        } else {
            $messages[] = "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
}

echo implode("<br>", $messages);
exit();
?>