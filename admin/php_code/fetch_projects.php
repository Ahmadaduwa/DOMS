<?php
// Connect to database (replace with your database credentials)
require("./../../php_code/dbconnect.php");

// Query to fetch project titles
$sql = "SELECT id, title FROM documents";
$result = $conn->query($sql);

$projects = array();
if ($result->num_rows > 0) {
    // Fetch project titles
    while ($row = $result->fetch_assoc()) {
        $projects[] = array("id" => $row['id'], "title" => $row['title']);
    }
}

$response = array("status" => "success", "projects" => $projects);
echo json_encode($response);

$conn->close();
?>
