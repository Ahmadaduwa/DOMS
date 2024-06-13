<?php
session_start();
require('dbconnect.php');

header('Content-Type: application/json');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['documentId']) && isset($_POST['userNumber']) && !empty($_POST['documentId']) && !empty($_POST['userNumber'])) {
    $documentId = intval($_POST['documentId']);
    $userNumber = intval($_POST['userNumber']);

    $sql = "UPDATE documents SET returned = 1, at_who = ? WHERE id = ?";
    $sql_get_title = "SELECT title FROM documents WHERE id = ?";
    $stmt_get_title = $conn->prepare($sql_get_title);
    $stmt_get_title->bind_param('i', $documentId);
    $stmt_get_title->execute();
    $stmt_get_title->bind_result($projectTitle);
    $stmt_get_title->fetch();
    $stmt_get_title->close();
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $userNumber, $documentId); // Change parameter order

    if ($stmt->execute()) {
        // Send LINE Notify
        $tokens = [
            7 => "HNt86ORGbL4bHRu80akhBzorWKeS32zt7Y4iJWLxZ3i",
            6 => "71w3kRItJbmAqB2BirV67gBG8j3zED8QkyXsXLYvgJi",
            5 => "MlChvVq43qrGPmylD8wBqSAHcXIBQMROXbdwKWnrlTi",
            4 => "LD0uGw9xxHwR2z6O6YYswbSLeXbrIw28UYBsmEiHXcl",
            3 => "LFRmmw1Ylb76nmcisFw1ycFkdXc1BL6s5jJ47puF5mB",
            2 => "RQkfLX3V2yW07T9xUUH2YwopN4LpR376mLwLTdFuPhy",
            1 => "8DGBDmgd7DyOVJ5IlfsOpwgWggOxPeW33Pm2o3SDcy0"
        ];

        if (array_key_exists($userNumber, $tokens)) {
            $sToken = $tokens[$userNumber];
            $message = "โครงการ \"$projectTitle\" ถูกตีกลับ";
            sendLineNotify($sToken, $message);
        } else {
            echo json_encode(['success' => false, 'error' => 'Token not found for user']);
        }

        echo json_encode(['success' => true]);
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
