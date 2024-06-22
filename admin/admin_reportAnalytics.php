<?php require('helper/admincheck.php') ?>

<?php

$searchValue = '';
$userType = '';
$users = [];

if (isset($_GET['user_type']) && !empty($_GET['user_type'])) {
    $userType = $_GET['user_type'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE (username = ?)");
    $stmt->bind_param("s",$userType);
    $stmt->execute();
    $result = $stmt->get_result();
    $users = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Reports & Analytics</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Include SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="./style.css">
    <style>
        .chart-container {
            position: relative;
            height: 40vh;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="sidebar">
        <a href="./admin_dashboard.php"><i class="fas fa-tachometer-alt"></i>Dashboard</a>
        <a href="./admin_docManage.php"><i class="fas fa-file-alt"></i>Document Management</a>
        <a href="./admin_projectManage.php"><i class="fas fa-project-diagram"></i>Project Management</a>
        <a href="./admin_userManage.php"><i class="fas fa-users"></i>User Management</a>
        <a href="./admin_setting.php"><i class="fas fa-cogs"></i>Settings</a>
        <a href="./admin_reportAnalytics.php"><i class="fas fa-chart-line"></i>Reports & Analytics</a>
        <a href="#" onclick="logout();"><i class="fas fa-sign-out-alt"></i>Logout</a>
    </div>
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="#">Admin Dashboard</a>
    </nav>

    <div class="main-content">
        <div id="reports" class="container-fluid">
            <h1 class="mt-4">Reports & Analytics</h1>
            <div class="card">
                <div class="card-body">
                    <i class="fas fa-chart-line"></i>
                    <h5 class="card-title">User Activity</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="userActivityChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <i class="fas fa-file-alt"></i>
                    <h5 class="card-title">Document Uploads</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="documentUploadsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
<footer>
    <!-- Include SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function logout() {
            event.preventDefault();
            // ใช้ SweetAlert เพื่อแสดงข้อความยืนยัน
            Swal.fire({
                title: "คุณแน่ใจหรือไม่?",
                text: "คุณต้องการออกจากระบบหรือไม่?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "ใช่, ออก",
            }).then((result) => {
                // หากผู้ใช้กดตกลง
                if (result.isConfirmed) {
                    // ทำการล็อคเอาท์
                    window.location.href = "./../php_code/logout.php";
                }
            });
        }

        const ctx1 = document.getElementById('userActivityChart').getContext('2d');
        const userActivityChart = new Chart(ctx1, {
            type: 'line',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June'],
                datasets: [{
                    label: 'User Activity',
                    data: [65, 59, 80, 81, 56, 55],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const ctx2 = document.getElementById('documentUploadsChart').getContext('2d');
        const documentUploadsChart = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June'],
                datasets: [{
                    label: 'Document Uploads',
                    data: [28, 48, 40, 19, 86, 27],
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</footer>
</html>