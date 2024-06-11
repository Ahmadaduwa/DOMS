<?php
if (session_status() === PHP_SESSION_NONE) { // Ensure session is started
    session_start();
}

// Database connection
require('dbconnect.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to fetch comments
function fetchComments($conn, $documentId)
{
    $comments = [];
    $sql_comments = "SELECT date, comment FROM comments WHERE document_id = ?";
    $stmt_comments = $conn->prepare($sql_comments);
    $stmt_comments->bind_param('i', $documentId);
    $stmt_comments->execute();
    $result_comments = $stmt_comments->get_result();

    while ($row_comment = $result_comments->fetch_assoc()) {
        $comments[] = $row_comment;
    }

    $stmt_comments->close();
    return $comments;
}

// Function to fetch files
function fetchFiles($conn, $documentId)
{
    $files = [];
    $sql_files = "SELECT file_path FROM files WHERE document_id = ?";
    $stmt_files = $conn->prepare($sql_files);
    $stmt_files->bind_param('i', $documentId);
    $stmt_files->execute();
    $result_files = $stmt_files->get_result();

    while ($row_file = $result_files->fetch_assoc()) {
        $files[] = $row_file['file_path'];
    }

    $stmt_files->close();
    return $files;
}

// Check if the request is an AJAX request to fetch files
if (isset($_POST['cardId'])) {
    $cardId = $_POST['cardId'];
    $files = fetchFiles($conn, $cardId);

    if (count($files) > 0) {
        foreach ($files as $file) {
            echo "<a href='" . $file . "' target='_blank'>" . basename($file) . "</a><br>";
        }
    } else {
        echo "No files found for this document.";
    }
} else {

    if (isset($_SESSION['number'])) {
        $userNumber = $_SESSION['number'];

        // Use a direct query without parameters as there are no dynamic parts in the query
        $sql = "SELECT d.*, u.name AS owner_name FROM documents d JOIN users u ON d.owner = u.number WHERE d.returned = 0 AND d.at_who = 0";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $documentId = $row['id'];
                $files = fetchFiles($conn, $documentId);
                $comments = fetchComments($conn, $documentId);

                echo "<div class='card mb-3' data-id='" . $documentId . "'>";
                echo "<div class='card-body d-flex'>";
                echo "<div class='card-details' style='flex: 1;'>";
                echo "<h5 class='card-title'>" . htmlspecialchars($row['title']) . "</h5>";
                echo "<div class='additional-info' style='display: none;'>";
                echo "<div class='cont'>";
                echo "<div class='left'>";
                echo "<p class='card-text'><strong>Submitter:</strong> " . htmlspecialchars($row['owner_name']) . "</p>";
                echo "<p class='card-text'><strong>Date:</strong> " . htmlspecialchars($row['date']) . "</p>";
                echo "<p class='card-text'><strong>Academic Year:</strong> " . htmlspecialchars($row['academic_year']) . " <strong>Term:</strong> " . htmlspecialchars($row['term']) . "</p>";
                echo "<p class='card-text'><strong>Description:</strong> " . htmlspecialchars($row['description']) . "</p>";
                echo "<p class='card-text'><strong>Capacity:</strong> " . htmlspecialchars($row['capacity']) . " people</p>";
                echo "<p class='card-text'><strong>Responsible:</strong> " . htmlspecialchars($row['responsible']) . " <strong>Phone:</strong> " . htmlspecialchars($row['phone']) . "</p>";
                if (count($files) > 0) {
                    echo "<p class='card-text'><strong>Files:</strong><br>";
                    foreach ($files as $file) {
                        echo "<a href='" . $file . "' target='_blank'>" . basename($file) . "</a><br>";
                    }
                    echo "</p>";
                } else {
                    echo "<p class='card-text'>No files found for this document.</p>";
                }
                echo "</div>";
                echo "<div class='card-comments right'>";
                if (count($comments) > 0) {
                    echo "<p class='card-text'><strong>Comments:</strong><br>";
                    foreach ($comments as $comment) {
                        echo "<p><strong>Date:</strong> " . htmlspecialchars($comment['date']) . "<br>" . htmlspecialchars($comment['comment']) . "</p>";
                    }
                    echo "</p>";
                } else {
                    echo "<p class='card-text'>No comments found for this document.</p>";
                }
                echo "</div>";
                echo "</div>";
                echo "</div>"; // Close additional-info div
                echo "<button class='btn btn-sm btn-secondary view-more-btn mt-2'>ดูเพิ่มเติม</button>";
                echo "<button type='button' class='btn btn-sm btn-danger mt-2 cancel-approve-btn' data-id='" . $documentId . "'>ยกเลิกอนุมัติโครงการ</button>";
                echo "</div>"; // Close card-details div
                echo "</div>"; // Close card-body div
                echo "</div>"; // Close card div
            }
        } else {
            echo "<div class='alert alert-warning' role='alert'>No documents found.</div>";
        }
    } else {
        echo "<div class='alert alert-danger' role='alert'>User is not logged in.</div>";
    }
    $conn->close();
}
?>