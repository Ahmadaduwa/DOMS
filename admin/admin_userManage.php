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
    <title>Admin Dashboard - User Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Include SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="./style.css">
    <style>
        .btn-sm {
            padding: 0.25rem 0.5rem;
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
        <div id="user-management" class="container-fluid">
            <h1 class="mt-4">User Management</h1>
            <div class="table-container">
            <div class=" mb-3">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search ID...">
                </div>
                <table class="table table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Username</th>
                            <th scope="col">Email</th>
                            <th scope="col">Role</th>
                            <th scope="col">Status</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>john_doe</td>
                            <td>john_doe@example.com</td>
                            <td>Admin</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td>
                                <button class="btn btn-info btn-sm"><i class="fas fa-eye"></i> View</button>
                                <button class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button>
                                <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">2</th>
                            <td>jane_doe</td>
                            <td>jane_doe@example.com</td>
                            <td>User</td>
                            <td><span class="badge badge-secondary">Inactive</span></td>
                            <td>
                                <button class="btn btn-info btn-sm"><i class="fas fa-eye"></i> View</button>
                                <button class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button>
                                <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</button>
                            </td>
                        </tr>

                        <!-- Add more rows as needed -->
                    </tbody>
                </table>
                <button class="btn" onclick="showAddUserModal();" style="margin-bottom: 0px" ;><i class="fas fa-user-plus"></i> Add User</button>
            </div>
        </div>
        <!-- Other sections such as Document Management, Project Management, Settings, Reports & Analytics can go here -->
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addUserForm">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="role">Role</label>
                            <input type="role" class="form-control" id="role" name="role" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="addUser();">Add User</button>
                </div>
            </div>
        </div>
    </div>

    
</body>
<footer>
    <script>
        function showAddUserModal() {
            $('#addUserModal').modal('show');
        }

        function addUser() {
            var username = $('#username').val();
            var email = $('#email').val();
            var password = $('#password').val();
            var role = $('#role').val();

            alert('User added successfully!');
            $('#addUserModal').modal('hide');
        }

        $(document).ready(function() {
            // ฟังก์ชันกรองแถวของตารางโดยพิจารณาค่าใน input
            $("#searchInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#user-management table tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });

        // ฟังก์ชันเพื่อแสดง Modal สำหรับเพิ่มผู้ใช้
        function showAddUserModal() {
            $('#addUserModal').modal('show');
        }

        // ฟังก์ชันเพื่อเพิ่มผู้ใช้
        function addUser() {
            var username = $('#username').val();
            var email = $('#email').val();
            var password = $('#password').val();
            var role = $('#role').val();

            alert('User added successfully!');
            $('#addUserModal').modal('hide');
        }
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Include SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</footer>
</html>