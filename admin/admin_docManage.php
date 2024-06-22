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
    <title>Admin Dashboard - Document Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Include SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="./style.css">
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
        <div id="document-management" class="container-fluid">
            <h1 class="mt-4">Document Management</h1>
            <div class="table-container">
                <div class=" mb-3">
                    <input type="text" id="searchInput" class="form-control" onkeyup="filterTable()" placeholder="Search file name...">
                </div>
                <table class="table table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Document Name</th>
                            <th scope="col" class="text-center">Project</th>
                            <th scope="col" class="text-center">From</th>
                            <th scope="col" class="text-center">Date Submitted</th>
                            <th scope="col" class="text-center">Status</th>
                            <th scope="col" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Connect to database (replace with your database credentials)
                        require("./../php_code/dbconnect.php");

                        // Function to sanitize and validate input
                        function sanitize_input($input)
                        {
                            // Remove illegal characters
                            $input = filter_var($input, FILTER_SANITIZE_STRING);
                            return $input;
                        }

                        // Get search term
                        $search = isset($_GET['search']) ? sanitize_input($_GET['search']) : '';

                        // Query to fetch data from database
                        $sql = "SELECT f.id, f.document_id, f.file_path, d.title, u.name AS owner_name, d.date AS document_date, d.returned, d.at_who
                                FROM files f
                                LEFT JOIN documents d ON f.document_id = d.id
                                LEFT JOIN users u ON d.owner = u.id";

                        // Append search condition if search term exists
                        if (!empty($search)) {
                            $sql .= " WHERE f.document_id LIKE '%$search%'";
                        }

                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            // Output data of each row
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<th scope='row'>" . $row['id'] . "</th>";
                                $filename = basename($row['file_path']);
                                echo "<td><a href='" . $row['file_path'] . "' target='_blank'>" . $filename . "</a></td>";
                                echo "<td class='text-center'>" . $row['title'] . "</td>";
                                echo "<td class='text-center'>" . $row['owner_name'] . "</td>"; // Display owner's name
                                echo "<td class='text-center'>" . $row['document_date'] . "</td>"; // Display document date

                                // Determine $at_who_name based on $row['document_id']
                                $at_who_name = "Undefined";
                                $document_id = $row['document_id'];

                                // Query to find $at_who_name from users table based on $document_id
                                $query_at_who = "SELECT u.name FROM users u 
                                             JOIN documents d ON u.id = d.at_who 
                                             WHERE d.id = $document_id";
                                $result_at_who = $conn->query($query_at_who);

                                if ($result_at_who->num_rows > 0) {
                                    $row_at_who = $result_at_who->fetch_assoc();
                                    $at_who_name = $row_at_who['name'];
                                }

                                // Display Test Status based on conditions
                                echo "<td class='text-center'>";
                                if ($row['returned'] == 1) {
                                    echo "<span class='status-reject'>ถูกตีกลับ</span>";
                                } elseif ($row['returned'] == 2) {
                                    echo "<span class='status-outline'>กำลังทำโครงร่าง</span>"; // Adjust color based on your preference
                                } elseif ($row['returned'] == 0) {
                                    if ($row['at_who'] >= 1 && $row['at_who'] <= 7) {
                                        echo "<span class='status-pending'>รอ " . $at_who_name . " อนุมัติโครงการ</span>";
                                    } elseif ($row['at_who'] == 0) {
                                        echo "<span class='status-approve'>อนุมัติโครงการแล้ว</span>";
                                    } else {
                                        echo "Undefined"; // Handle undefined cases if any
                                    }
                                }
                                echo "</td>";

                                echo "<td class='text-center'>";
                                echo "<button class='btn btn-warning btn-sm editBtn mr-2' data-id='" . $row['id'] . "'><i class='fas fa-edit'></i> Edit</button>";
                                echo "<button class='btn btn-danger btn-sm deleteBtn' data-id='" . $row['id'] . "'><i class='fas fa-trash'></i> Delete</button>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>No documents found.</td></tr>";
                        }
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal HTML -->
    <div class="modal fade" id="editDocumentModal" tabindex="-1" role="dialog" aria-labelledby="editDocumentModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDocumentModalLabel">Edit Document</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editDocumentForm">
                        <input type="hidden" id="documentId" name="documentId">
                        <div class="form-group">
                            <label for="documentTitle">Document Name</label>
                            <input type="text" class="form-control" id="documentTitle" name="documentTitle" readonly>
                        </div>
                        <div class="form-group">
                            <label for="projectDropdown">Select Project</label>
                            <input type="text" class="form-control mb-2" id="searchProjectInput" onkeyup="filterProjects()" placeholder="Search for projects...">
                            <select class="form-control" id="projectDropdown" name="projectDropdown">
                                <!-- Options will be populated dynamically -->
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="saveChanges()">Save changes</button>
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

        function filterTable() {
            // Declare variables
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.querySelector(".table");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0]; // Column where ID is displayed
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }

        // ใช้การคลิกที่ปุ่ม Delete ที่มี class .deleteBtn
        $(document).on("click", ".deleteBtn", function() {
            var id = $(this).data("id");

            Swal.fire({
                title: "คุณแน่ใจหรือไม่?",
                text: "คุณจะไม่สามารถเรียกคืนได้!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "ใช่, ลบเลย!",
            }).then((result) => {
                if (result.isConfirmed) {
                    // ส่งคำขอลบไปยัง delete_document.php โดยใช้ Ajax
                    $.ajax({
                        url: "./delete_document.php",
                        type: "POST",
                        data: {
                            id: id
                        },
                        success: function(response) {
                            var responseData = JSON.parse(response);
                            if (responseData.status === "success") {
                                // ลบแถวที่มี ID ตรงกับข้อมูลที่ถูกลบ
                                $("button[data-id='" + id + "']").closest("tr").remove();
                                Swal.fire("ลบแล้ว!", "เอกสารถูกลบแล้ว.", "success");
                            } else {
                                Swal.fire("เกิดข้อผิดพลาด!", responseData.message, "error");
                            }
                        },
                        error: function() {
                            Swal.fire("เกิดข้อผิดพลาด!", "ไม่สามารถลบเอกสารได้ในขณะนี้", "error");
                        }
                    });
                }
            });
        });
        $(document).on("click", ".editBtn", function() {
            var id = $(this).data("id");
            var row = $(this).closest("tr");
            var documentTitle = row.find("td:eq(1)").text();

            $("#documentId").val(id);
            $("#documentTitle").val(documentTitle);

            // Fetch project titles from the server
            $.ajax({
                url: "./php_code/fetch_projects.php",
                type: "GET",
                success: function(response) {
                    var responseData = JSON.parse(response);
                    if (responseData.status === "success") {
                        var projects = responseData.projects;
                        var projectDropdown = $("#projectDropdown");
                        projectDropdown.empty();
                        projects.forEach(function(project) {
                            projectDropdown.append(new Option(project.title, project.id));
                        });
                    } else {
                        Swal.fire("Error!", "Unable to fetch projects.", "error");
                    }
                },
                error: function() {
                    Swal.fire("Error!", "Unable to fetch projects.", "error");
                }
            });

            // Show the modal
            $("#editDocumentModal").modal("show");
        });

        function filterProjects() {
            var input, filter, select, options, i, txtValue;
            input = document.getElementById("searchProjectInput");
            filter = input.value.toUpperCase();
            select = document.getElementById("projectDropdown");
            options = select.getElementsByTagName("option");

            for (i = 0; i < options.length; i++) {
                txtValue = options[i].textContent || options[i].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    options[i].style.display = "";
                } else {
                    options[i].style.display = "none";
                }
            }
        }


        function saveChanges() {
            var formData = $("#editDocumentForm").serialize();
            $.ajax({
                url: "./php_code/update_document.php",
                type: "POST",
                data: formData,
                success: function(response) {
                    // ไม่ต้องใช้ JSON.parse() เนื่องจากไม่ได้รับ JSON มา
                    // ใช้ response ตรงๆ ในการแสดง Alert หรือประมวลผลต่อไป
                    Swal.fire({
                        title: 'แก้ไขเสร็จเรียบร้อย!',
                        text: 'ข้อมูลได้รับการอัปเดตเรียบร้อยแล้ว',
                        icon: 'success',
                        confirmButtonText: 'ตกลง'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $("#editDocumentModal").modal("hide");
                            location.reload(); // รีโหลดหน้าเพื่อทำให้มันแสดงการเปลี่ยนแปลงที่เกิดขึ้น
                        }
                    });
                },
                error: function() {
                    Swal.fire("ข้อผิดพลาด!", "ไม่สามารถบันทึกการเปลี่ยนแปลงได้", "error");
                }
            });
        }
    </script>
</footer>

</html>