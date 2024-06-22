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
            overflow-y: auto; /* เพิ่ม overflow-y เพื่อให้มีการส่วนของข้อมูลหายไป */
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

        .card {
            margin-bottom: 20px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card h5 {
            font-weight: bold;
        }

        .card .card-body {
            display: flex;
            align-items: center;
        }

        .card .card-body i {
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

        .navbar {
            margin-left: 250px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .navbar .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }

        .table-container {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .status-pending {
            color: orange;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .status-reject {
            color: red;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .status-approve {
            color: green;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .status-outline {
            color: cadetblue;
            padding: 5px 10px;
            border-radius: 5px;
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
                                echo "<button class='btn btn-info btn-sm'><i class='fas fa-eye'></i> Edit</button>";
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
    </script>
</body>

</html>
