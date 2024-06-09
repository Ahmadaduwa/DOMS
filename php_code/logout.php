<?php
session_start();

// ล้าง session และทำลาย session
session_unset();
session_destroy();

// ลบคุกกี้ที่เกี่ยวข้อง
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-3600, '/');
}

// ส่งกลับไปยังหน้า login
header("Location: ./../index.php");
exit();
?>