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
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f6f9;
        }
        .sidebar {
            height: 100vh;
            position: fixed;
            width: 250px;
            background-color: #343a40;
            padding-top: 20px;
        }
        .sidebar a {
            padding: 15px;
            text-align: center;
            text-decoration: none;
            display: block;
            color: white;
            font-size: 18px;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .sidebar a i {
            margin-right: 10px;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .navbar {
            margin-left: 250px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .navbar .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .card {
            margin-bottom: 20px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-body {
            display: flex;
            align-items: center;
        }
        .card-body i {
            font-size: 3rem;
            margin-right: 20px;
        }
        .bg-primary-light {
            background-color: #007bff;
            color: white;
        }
        .bg-success-light {
            background-color: #28a745;
            color: white;
        }
        .bg-warning-light {
            background-color: #ffc107;
            color: white;
        }
        .bg-danger-light {
            background-color: #dc3545;
            color: white;
        }
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
