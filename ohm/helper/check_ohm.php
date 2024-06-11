<?php
    session_start();

    // ตรวจสอบว่ามี session หรือไม่ และว่า 'number' เป็น 1 หรือไม่
    if ((!isset($_SESSION['number']) || $_SESSION['number'] != 1) && $_SESSION['number'] != 0) {
        // ถ้าไม่มี session หรือ 'number' ไม่เป็น 1 ให้ redirect ไปหน้า login
        header("Location: index.php");
        exit();
    }
?>