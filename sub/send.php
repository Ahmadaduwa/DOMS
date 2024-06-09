<?php
session_start();

// ตรวจสอบว่ามี session หรือไม่ และว่า 'number' อยู่ในช่วง 2 ถึง 7 หรือไม่
if (!isset($_SESSION['number']) || ($_SESSION['number'] < 2 || $_SESSION['number'] > 7)) {
    // ถ้าไม่มี session หรือ 'number' ไม่อยู่ในช่วง 2 ถึง 7 ให้ redirect ไปหน้า login
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Doms</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Include SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="./style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            padding-top: 75px;
        }

        .navbar {
            background-color: #007bff;
        }

        .navbar-brand {
            color: #fff;
            font-weight: bold;
        }

        .navbar-nav .nav-link {
            color: #fff;
        }

        .navbar-nav .nav-link:hover {
            color: #d4d4d4;
        }

        .navbar .container {
            justify-content: space-between;
        }

        .card {
            transition: transform 0.3s ease-in-out;
        }

        .card:hover {
            transform: scale(1.02);
        }

        .container {
            margin-top: 5px;
            margin-bottom: 5px;
        }

        .additional-info {
            display: none;
        }

        .btn-sm {
            margin-right: 5px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">E-Doms</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="./home.php" data-target="home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./status.php" data-target="statusDocument">Status</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./send.php" data-target="sendDocument">Send</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./ro.php" data-target="documentDraft">R/O</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./cont.php" data-target="contactForms">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="logoutBtn nav-link" href="#">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <!-- Send Document Section -->
        <div id="sendDocument" class="section active">
            <h2>Send Document</h2>
            <form id="uploadForm" action="./../php_code/upload_document.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" id="title" name="title" maxlength="150" required>
                </div>
                <div class="form-group">
                    <label for="main" class="form-label">Main side</label>
                    <select id="main" name="main" class="form-select">
                        <option>-</option>
                        <option>ความกตัญญู</option>
                        <option>การรู้วินัย</option>
                        <option>การมีใจอาสา</option>
                        <option>การพัฒนาภาวะผู้นำ</option>
                        <option>ความรักชาติ</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="start_date">From Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                </div>
                <div class="form-group">
                    <label for="end_date">To Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" required>
                </div>
                <div class="form-group">
                    <label for="academic_year" class="form-label">Academic Year</label>
                    <select id="academic_year" name="academic_year" class="form-select">
                        <option>-</option>
                        <option>2024</option>
                        <option>2025</option>
                        <option>2026</option>
                        <option>2027</option>
                        <option>2028</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="term" class="form-label">Term</label>
                    <select id="term" name="term" class="form-select">
                        <option>-</option>
                        <option>1</option>
                        <option>2</option>
                        <option>summer</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="capacity">Capacity (people)</label>
                    <input type="number" class="form-control" id="capacity" name="capacity" required>
                </div>
                <div class="form-group">
                    <label for="responsible">Responsible Person</label>
                    <input type="text" class="form-control" id="responsible" name="responsible" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" class="form-control" id="phone" name="phone" required>
                </div>
                <div class="form-group custom-file">
                    <input type="file" class="custom-file-input" id="file" name="files[]" multiple required>
                    <label class="custom-file-label" for="file"></label>
                    <div id="fileList"></div>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
            <div id="responseMessage"></div>
        </div>
    </div>
    <div style="height: 100px;"></div>

    <!-- Modal for Alert -->
    <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="alertModalLabel">Alert</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="alertMessage">
                    <!-- Alert message will be displayed here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="footer" style="position: fixed; bottom: 0; right: 0; left: 0; background-color: transparent;">
        <p style="color: black; text-align: right; margin-right: 10px; font-size: 14px; font-family: Arial, sans-serif;">&copy; 2024 SUB IT Team. All rights reserved.</p>
    </div>
</body>
<footer>
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- Include SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Include custom JS -->
    <script src="./scripts.js"></script>
    <script>
        $(document).ready(function() {
            // Upload form (หน้าส่งเอกสาร)
            $("#uploadForm").on("submit", function(e) {
                e.preventDefault(); // Prevent default form submission

                Swal.fire({
                    title: "คุณแน่ใจหรือไม่?",
                    text: "คุณต้องการที่จะอัปโหลดเอกสารนี้หรือไม่?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "ใช่, อัปโหลด!",
                }).then((result) => {
                    if (result.isConfirmed) {
                        var formData = new FormData(this);

                        $.ajax({
                            url: "./../php_code/upload_document.php",
                            type: "POST",
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function(response) {
                                Swal.fire({
                                    title: "สำเร็จ",
                                    text: "เอกสารถูกอัปโหลดเรียบร้อยแล้ว",
                                    icon: "success",
                                    confirmButtonText: "ตกลง",
                                }).then(function() {
                                    location.reload();
                                });
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    title: "ข้อผิดพลาด",
                                    text: "มีข้อผิดพลาดในการอัปโหลดเอกสาร",
                                    icon: "error",
                                    confirmButtonText: "ตกลง",
                                });
                            },
                        });
                    }
                });
            });
        });
    </script>
</footer>

</html>