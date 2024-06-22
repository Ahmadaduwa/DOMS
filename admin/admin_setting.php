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
    <title>Admin Dashboard - Settings</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Include SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="./style.css">
    <style>
        .form-group label {
            font-weight: bold;
        }

        .form-control {
            border-radius: 5px;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 5px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
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
        <div id="settings" class="container-fluid">
            <h1 class="mt-4">Settings</h1>
            <div class="card">
                <div class="card-body">
                    <i class="fas fa-cogs"></i>
                    <h5 class="card-title">General Settings</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="form-group">
                            <label for="siteName">Site Name</label>
                            <input type="text" class="form-control" id="siteName" placeholder="Enter site name">
                        </div>
                        <div class="form-group">
                            <label for="adminEmail">Admin Email</label>
                            <input type="email" class="form-control" id="adminEmail" placeholder="Enter admin email">
                        </div>
                        <div class="form-group">
                            <label for="timezone">Timezone</label>
                            <select class="form-control" id="timezone">
                                <option>UTC</option>
                                <option>GMT</option>
                                <option>EST</option>
                                <option>PST</option>
                                <!-- Add more timezones as needed -->
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <i class="fas fa-shield-alt"></i>
                    <h5 class="card-title">Security Settings</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="form-group">
                            <label for="passwordPolicy">Password Policy</label>
                            <select class="form-control" id="passwordPolicy">
                                <option>Weak</option>
                                <option>Medium</option>
                                <option>Strong</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="twoFactorAuth">Two-Factor Authentication</label>
                            <select class="form-control" id="twoFactorAuth">
                                <option>Disabled</option>
                                <option>Enabled</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
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