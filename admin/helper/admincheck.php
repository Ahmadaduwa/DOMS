<?php
    require('./../php_code/dbconnect.php');

    session_start();

    // if ((!isset($_SESSION['name']) && !($_SESSION['number'] != 0))) {
    //     header("Location: ./../index.php");
    //     exit;
    // }
    
    if (!isset($_SESSION['name'])) {
        session_destroy();
        session_unset();
        header("Location: ./../index.php");
        exit;
    }

    if ($_SESSION['number'] != 0) {
        session_destroy();
        session_unset();
        header("Location: ./../index.php");
        exit;
    }

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $_SESSION['name']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user) {
        $username = $user['username'];
        $name = $user['name'];
        $number = $user['number'];
    } else {
        header("Location: ./../index.php");
        exit;
    }
?>