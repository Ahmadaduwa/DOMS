<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $documentId = $data['id'];

    // ตรวจสอบว่ามี documentId ถูกส่งมาหรือไม่
    if (!isset($documentId)) {
        echo json_encode(['success' => false, 'error' => 'No document ID provided.']);
        exit();
    }

    // เชื่อมต่อกับฐานข้อมูล
    require('dbconnect.php');

    // ตรวจสอบการเชื่อมต่อ
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // อัปเดตค่า level และ at_who ในตาราง documents
    $sql = "UPDATE documents SET level = level - 1, at_who = at_who - 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $documentId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
