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

    // ดึงข้อมูล level, owner และ title ปัจจุบันของเอกสาร
    $sql = "SELECT level, owner, title FROM documents WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $documentId);
    $stmt->execute();
    $stmt->bind_result($currentLevel, $owner, $title);
    $stmt->fetch();
    $stmt->close();

    // ดึงชื่อของ owner จากตาราง users
    $sql = "SELECT name FROM users WHERE number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $owner);
    $stmt->execute();
    $stmt->bind_result($ownerName);
    $stmt->fetch();
    $stmt->close();

    // ตรวจสอบค่า level และกำหนด token ที่เหมาะสม
    $tokens = [
        7 => "71w3kRItJbmAqB2BirV67gBG8j3zED8QkyXsXLYvgJi",
        6 => "MlChvVq43qrGPmylD8wBqSAHcXIBQMROXbdwKWnrlTi",
        5 => "LD0uGw9xxHwR2z6O6YYswbSLeXbrIw28UYBsmEiHXcl",
        4 => "LFRmmw1Ylb76nmcisFw1ycFkdXc1BL6s5jJ47puF5mB",
        3 => "RQkfLX3V2yW07T9xUUH2YwopN4LpR376mLwLTdFuPhy",
        2 => "8DGBDmgd7DyOVJ5IlfsOpwgWggOxPeW33Pm2o3SDcy0",
        1 => ""
    ];

    if (!array_key_exists($currentLevel, $tokens)) {
        echo json_encode(['success' => false, 'error' => 'Invalid document level.']);
        exit();
    }

    $sToken = $tokens[$currentLevel];

    // อัปเดตค่า level และ at_who ในตาราง documents
    $sql = "UPDATE documents SET level = level - 1, at_who = at_who - 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $documentId);

    if ($stmt->execute()) {
        // ตรวจสอบว่า token ไม่ใช่ค่าว่างก่อนส่งข้อความเข้าไลน์
        if (!empty($sToken)) {
            $message = "$ownerName ได้ยืนขออนุมัติโครงการ \"$title\"";
            sendLineNotify($sToken, $message);
        }
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}

function sendLineNotify($token, $message) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://notify-api.line.me/api/notify");
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "message=" . urlencode($message));
    
    $headers = array('Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer ' . $token,);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($ch);

    if (curl_error($ch)) {
        echo 'error:' . curl_error($ch);
    } else {
        $result_ = json_decode($result, true);
        echo "status : " . $result_['status'];
        echo "message : " . $result_['message'];
    }
    curl_close($ch);
}
?>