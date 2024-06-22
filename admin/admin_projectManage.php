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
    <title>Admin Dashboard - Project Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Include SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="./style.css">
    <style>
        .status-pending {
            color: #ffc107;
        }

        .status-approved {
            color: #28a745;
        }

        .status-rejected {
            color: #dc3545;
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
        <div id="project-management" class="container-fluid">
            <h1 class="mt-4">Project Management</h1>
            <div class="table-container">
                <div class=" mb-3">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search ID...">
                </div>
                <table id="projectTable" class="table table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Project Name</th>
                            <th scope="col">From</th>
                            <th scope="col">Date Submitted</th>
                            <th scope="col">Status</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>Website Redesign</td>
                            <td>Development</td>
                            <td>2024-06-20</td>
                            <td><span class="status-pending">Pending</span></td>
                            <td>
                                <button class="btn btn-info btn-sm"><i class="fas fa-eye"></i> View</button>
                                <button class="btn btn-warning btn-sm" onclick="editProject();"><i class="fas fa-edit"></i> Edit</button>
                                <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">2</th>
                            <td>Marketing Campaign</td>
                            <td>Marketing</td>
                            <td>2024-06-18</td>
                            <td><span class="status-approved">Approved</span></td>
                            <td>
                                <button class="btn btn-info btn-sm"><i class="fas fa-eye"></i> View</button>
                                <button class="btn btn-warning btn-sm" onclick="editProject();"><i class="fas fa-edit"></i> Edit</button>
                                <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">3</th>
                            <td>New Product Launch</td>
                            <td>Product</td>
                            <td>2024-06-15</td>
                            <td><span class="status-rejected">Rejected</span></td>
                            <td>
                                <button class="btn btn-info btn-sm"><i class="fas fa-eye"></i> View</button>
                                <button class="btn btn-warning btn-sm" onclick="editProject();"><i class="fas fa-edit"></i> Edit</button>
                                <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</button>
                            </td>
                        </tr>
                        <!-- Add more rows as needed -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Project Modal -->
    <div class="modal fade" id="editProjectModal" tabindex="-1" aria-labelledby="editProjectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProjectModalLabel">Edit Project</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editProjectForm">
                        <div class="form-group">
                            <label for="editProjectName">Project Name</label>
                            <input type="text" class="form-control" id="editProjectName" name="editProjectName" required>
                        </div>
                        <div class="form-group">
                            <label for="editProjectFrom">From</label>
                            <input type="text" class="form-control" id="editProjectFrom" name="editProjectFrom" required>
                        </div>
                        <div class="form-group">
                            <label for="editDateSubmitted">Date Submitted</label>
                            <input type="text" class="form-control" id="editDateSubmitted" name="editDateSubmitted" required>
                        </div>
                        <div class="form-group">
                            <label for="editProjectStatus">Status</label>
                            <select class="form-control" id="editProjectStatus" name="editProjectStatus">
                                <option value="Pending">Pending</option>
                                <option value="Approved">Approved</option>
                                <option value="Rejected">Rejected</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="updateProject();">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Status Modal -->
    <div class="modal fade" id="editStatusModal" tabindex="-1" aria-labelledby="editStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editStatusModalLabel">Edit Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editStatusForm">
                        <div class="form-group">
                            <label for="editProjectStatusSelect">Select Status</label>
                            <select class="form-control" id="editProjectStatusSelect" name="editProjectStatusSelect">
                                <option value="Pending">Pending</option>
                                <option value="Approved">Approved</option>
                                <option value="Rejected">Rejected</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="updateStatus();">Update Status</button>
                </div>
            </div>
        </div>
    </div>

    
</body>
<footer>
    <!-- Include SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Function to filter table rows based on input value
            $("#searchInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#projectTable tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });

        // Function to handle edit button click (to populate data in edit modal)
        function editProject() {
            // Example: Fetch project data and populate the modal fields
            var projectName = "Example Project"; // Replace with actual data fetch
            var projectFrom = "Development"; // Replace with actual data fetch
            var dateSubmitted = "2024-06-20"; // Replace with actual data fetch
            var status = "Pending"; // Replace with actual data fetch

            // Populate modal fields
            $("#editProjectName").val(projectName);
            $("#editProjectFrom").val(projectFrom);
            $("#editDateSubmitted").val(dateSubmitted);
            $("#editProjectStatus").val(status);

            // Show modal
            $("#editProjectModal").modal("show");
        }

        // Function to handle status edit button click (to populate data in status edit modal)
        function editStatus() {
            // Example: Fetch current status and populate the status edit modal
            var currentStatus = "Pending"; // Replace with actual data fetch

            // Populate modal fields
            $("#editProjectStatusSelect").val(currentStatus);

            // Show modal
            $("#editStatusModal").modal("show");
        }

        // Function to update project details
        function updateProject() {
            // Example: Implement update logic here
            // Fetch values from form
            var editedProjectName = $("#editProjectName").val();
            var editedProjectFrom = $("#editProjectFrom").val();
            var editedDateSubmitted = $("#editDateSubmitted").val();
            var editedStatus = $("#editProjectStatus").val();

            // Perform update (replace with actual update logic)
            console.log("Project updated successfully!");
            // Close modal
            $("#editProjectModal").modal("hide");
        }

        // Function to update project status
        function updateStatus() {
            // Example: Implement status update logic here
            // Fetch new status value
            var updatedStatus = $("#editProjectStatusSelect").val();

            // Perform status update (replace with actual update logic)
            console.log("Status updated successfully!");
            // Close modal
            $("#editStatusModal").modal("hide");
        }

        // Example logout function (replace with actual logout functionality)
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