<?php
require('dbconnect.php');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id'])) {
    $documentId = $data['id'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Retrieve the level, owner, and title values
        $sql_get_info = "SELECT level, owner, title FROM documents WHERE id = ?";
        $stmt_get_info = $conn->prepare($sql_get_info);
        $stmt_get_info->bind_param('i', $documentId);
        $stmt_get_info->execute();
        $stmt_get_info->bind_result($level, $owner, $title);
        $stmt_get_info->fetch();
        $stmt_get_info->close();

        if ($level === null) {
            throw new Exception("Level not found for document ID: $documentId");
        }

        // Retrieve the owner's name
        $sql_get_owner_name = "SELECT name FROM users WHERE number = ?";
        $stmt_get_owner_name = $conn->prepare($sql_get_owner_name);
        $stmt_get_owner_name->bind_param('i', $owner);
        $stmt_get_owner_name->execute();
        $stmt_get_owner_name->bind_result($ownerName);
        $stmt_get_owner_name->fetch();
        $stmt_get_owner_name->close();

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

        // Send notification
        $tokens = [
            7 => "HNt86ORGbL4bHRu80akhBzorWKeS32zt7Y4iJWLxZ3i",
            6 => "71w3kRItJbmAqB2BirV67gBG8j3zED8QkyXsXLYvgJi",
            5 => "MlChvVq43qrGPmylD8wBqSAHcXIBQMROXbdwKWnrlTi",
            4 => "LD0uGw9xxHwR2z6O6YYswbSLeXbrIw28UYBsmEiHXcl",
            3 => "LFRmmw1Ylb76nmcisFw1ycFkdXc1BL6s5jJ47puF5mB",
            2 => "RQkfLX3V2yW07T9xUUH2YwopN4LpR376mLwLTdFuPhy",
            1 => "8DGBDmgd7DyOVJ5IlfsOpwgWggOxPeW33Pm2o3SDcy0"
        ];

        if (array_key_exists($level, $tokens)) {
            $sToken = $tokens[$level];
            $message = "$ownerName ได้ยืนขออนุมัติโครงการ \"$title\"";
            $notifyResult = sendLineNotify($sToken, $message);
        
            // ตรวจสอบผลลัพธ์จากการแจ้งเตือน LINE Notify
            if ($notifyResult['success']) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => $notifyResult['error']]);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Invalid level']);
        }
    } catch (Exception $e) {
        // Rollback transaction
        $conn->rollback();

        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

$conn->close();

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
        $notifyResult = ['success' => false, 'error' => curl_error($ch)];
    } else {
        $result_ = json_decode($result, true);
        $notifyResult = ['success' => true, 'message' => $result_['message']];
    }
    curl_close($ch);

    return $notifyResult;
}
?>
