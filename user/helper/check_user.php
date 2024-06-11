<?php
session_start();

// ตรวจสอบว่ามี session หรือไม่ และว่า 'number' มากกว่า 7 หรือไม่
if (!isset($_SESSION['number']) || $_SESSION['number'] <= 7) {
    // ถ้าไม่มี session หรือ 'number' มากกว่าหรือเท่ากับ 7 ให้ redirect ไปหน้า login
    header("Location: index.php");
    exit();
}
?>
