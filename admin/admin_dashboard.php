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
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Include SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="./style.css">
    <style>
        .recent-activities {
            margin-top: 20px;
        }
        .recent-activities .list-group-item {
            border-left: 5px solid transparent;
            font-size: 0.9rem;
            padding: 10px;
        }
        .recent-activities .list-group-item:hover {
            background-color: #f8f9fa;
        }
        .recent-activities .list-group-item i {
            margin-right: 10px;
        }
        .btn-submit {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-submit:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
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
        <div id="dashboard" class="container-fluid">
            <h1 class="mt-4">Dashboard</h1>
            <div class="row">
                <div class="col-md-3">
                    <div class="card bg-primary-light">
                        <div class="card-body">
                            <i class="fas fa-file-alt"></i>
                            <div>
                                <h5>Documents</h5>
                                <h3>150</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success-light">
                        <div class="card-body">
                            <i class="fas fa-users"></i>
                            <div>
                                <h5>Users</h5>
                                <h3>75</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning-light">
                        <div class="card-body">
                            <i class="fas fa-project-diagram"></i>
                            <div>
                                <h5>Projects</h5>
                                <h3>10</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger-light">
                        <div class="card-body">
                            <i class="fas fa-exclamation-circle"></i>
                            <div>
                                <h5>Issues</h5>
                                <h3>5</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4 recent-activities">
                <div class="card-body">
                    <h5 class="card-title">Recent Activities</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <i class="fas fa-upload"></i>
                            User John Doe uploaded a document.
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-check-circle"></i>
                            Project X was approved.
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-user-edit"></i>
                            User Jane Doe updated her profile.
                        </li>
                        <!-- Add more activities as needed -->
                    </ul>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">Pending Projects</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <div>
                                <h6>Project Alpha</h6>
                                <p>Submitted by John Smith</p>
                            </div>
                            <button class="btn btn-submit btn-sm">View & Approve</button>
                        </li>
                        <li class="list-group-item">
                            <div>
                                <h6>Project Beta</h6>
                                <p>Submitted by Jane Doe</p>
                            </div>
                            <button class="btn btn-submit btn-sm">View & Approve</button>
                        </li>
                        <!-- Add more pending projects as needed -->
                    </ul>
                </div>
            </div>
        </div>
    </div>

    
</body>
<footer>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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
    </script>
</footer>
</html>
