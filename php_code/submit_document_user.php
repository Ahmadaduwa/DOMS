<?php
require('dbconnect.php');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id'])) {
    $documentId = $data['id'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Retrieve the owner and title values
        $sql_get_info = "SELECT owner, title FROM documents WHERE id = ?";
        $stmt_get_info = $conn->prepare($sql_get_info);
        $stmt_get_info->bind_param('i', $documentId);
        $stmt_get_info->execute();
        $stmt_get_info->bind_result($owner, $title);
        $stmt_get_info->fetch();
        $stmt_get_info->close();

        if ($owner === null) {
            throw new Exception("Owner not found for document ID: $documentId");
        }

        // Retrieve the owner's name
        $sql_get_owner_name = "SELECT name FROM users WHERE number = ?";
        $stmt_get_owner_name = $conn->prepare($sql_get_owner_name);
        $stmt_get_owner_name->bind_param('i', $owner);
        $stmt_get_owner_name->execute();
        $stmt_get_owner_name->bind_result($ownerName);
        $stmt_get_owner_name->fetch();
        $stmt_get_owner_name->close();

        // Update the returned field and set level and at_who to 7
        $sql_update = "UPDATE documents SET returned = 0, level = 7, at_who = 7 WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param('i', $documentId);
        if (!$stmt_update->execute()) {
            throw new Exception("Error updating document fields: " . $stmt_update->error);
        }
        $stmt_update->close();

        // Commit transaction
        $conn->commit();

        // Send notification
        $sToken = "Q5pzuPW8pXT7ONAjyOYE3bPH24i1l2mKWK8Fqx3PiRJ";
        $currentDate = date("d/m/Y");
        $message = "ID: $documentId\nขออนุมัติโครงการ \"$title\"\nผู้ยื่นโครงการ: $ownerName\nวันที่ทำรายการ: $currentDate";
        $notifyResult = sendLineNotify($sToken, $message);

        // ตรวจสอบผลลัพธ์จากการแจ้งเตือน LINE Notify
        if ($notifyResult['success']) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $notifyResult['error']]);
        }
    } catch (Exception $e) {
        // Rollback transaction
        $conn->rollback();

        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'No document ID provided']);
}

$conn->close();

function sendLineNotify($token, $message)
{
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
        $notifyResult = ['success' => ($result_['status'] == 200), 'message' => $result_['message']];
    }
    curl_close($ch);

    return $notifyResult;
}
