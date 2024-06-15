<?php
session_start();
require('dbconnect.php');

header('Content-Type: application/json');

if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

if (isset($_POST['documentId']) && !empty($_POST['documentId']) && isset($_POST['userNumber']) && !empty($_POST['userNumber'])) {
    $documentId = intval($_POST['documentId']);
    $userNumber = intval($_POST['userNumber']);

    // ตรวจสอบการมีอยู่ของ $_SESSION['number']
    if (!isset($_SESSION['number'])) {
        echo json_encode(['error' => 'Session number is not set', 'session' => $_SESSION, 'post' => $_POST]);
        exit();
    }

    $currentDate = date('d/m/Y');

    // ดึงข้อมูล title และ owner จาก documents
    $sql_get_info = "SELECT title, owner FROM documents WHERE id = ?";
    $stmt_get_info = $conn->prepare($sql_get_info);
    $stmt_get_info->bind_param('i', $documentId);
    $stmt_get_info->execute();
    $stmt_get_info->bind_result($projectTitle, $owner);
    $stmt_get_info->fetch();
    $stmt_get_info->close();

    // ดึงชื่อผู้ยื่นโครงการ
    $sql_get_owner_name = "SELECT name FROM users WHERE number = ?";
    $stmt_get_owner_name = $conn->prepare($sql_get_owner_name);
    $stmt_get_owner_name->bind_param('i', $owner);
    $stmt_get_owner_name->execute();
    $stmt_get_owner_name->bind_result($ownerName);
    $stmt_get_owner_name->fetch();
    $stmt_get_owner_name->close();

    // ดึงชื่อผู้ที่ทำการตีกลับ
    $userName = $_SESSION['name'];

    // อัพเดท documents
    $sql = "UPDATE documents SET returned = 1, at_who = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $userNumber, $documentId);

    if ($stmt->execute()) {
        // ส่ง LINE Notify
        $tokens = [
            7 => "Q5pzuPW8pXT7ONAjyOYE3bPH24i1l2mKWK8Fqx3PiRJ",
            6 => "Mgh3wYjU11U8klMYRijZ4LxZprMufQQ8cTmcuMg6Xkh",
            5 => "NgTP9Q6UOLdXRpyNeWEVZ2VGcJRowTPL42pXaarlxCf",
            4 => "DT4Q5y5Y1htTzaOZGzMwZrm3qK9lBIItGoKdwOPfL9e",
            3 => "809iDieyaz4TOEUqm0zTYt92fPBpAFaYfFcw7TIy4aq",
            2 => "a0dUuzSHs1yY5Ps4uv4Xa7qrERWfziJ1Jp5WYgI90ja",
            1 => "Td75qr7OXw2BoJp9n43kMwCEpdU6sD4vhYTowDq3Jhb"
        ];

        if (array_key_exists($userNumber, $tokens)) {
            $sToken = $tokens[$userNumber];
            $message = "ID: $documentId\nโครงการ \"$projectTitle\" ถูกตีกลับ\nผู้ยื่นโครงการ: $ownerName\nถูกตีกลับโดย: $userName\nวันที่ทำรายการ: $currentDate";
            $notifyResult = sendLineNotify($sToken, $message);

            if ($notifyResult['success']) {
                echo json_encode(['success' => true, 'notify' => $notifyResult]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Notification failed', 'notify' => $notifyResult]);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Token not found for user']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'Invalid documentId or userNumber']);
}

$conn->close();

function sendLineNotify($token, $message) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://notify-api.line.me/api/notify");
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "message=" . urlencode($message));
    
    $headers = array('Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer ' . $token);
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
?>
