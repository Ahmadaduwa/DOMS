<?php
session_start();

// ตรวจสอบว่ามี session หรือไม่ และว่า 'number' อยู่ในช่วง 2 ถึง 7 หรือไม่
if ((!isset($_SESSION['number']) || ($_SESSION['number'] < 2 || $_SESSION['number'] > 7)) && $_SESSION['number'] != 0) {
    // ถ้าไม่มี session หรือ 'number' ไม่อยู่ในช่วง 2 ถึง 7 ให้ redirect ไปหน้า login
    header("Location: index.php");
    exit();
}

?>