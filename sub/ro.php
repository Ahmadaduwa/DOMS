<?php
    require('helper/check_sub.php');
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
        <!-- documentDraft Section -->
        <div id="documentDraft" class="section active">
            <h2>Returned Forms</h2>
            <div id="returnedDocs">
                <!-- Returned documents will be listed here -->
                <?php include './../php_code/fetch_returned_documents.php'; ?>
            </div>
            <h2>Project Outline</h2>
            <div id="draftDocs">
                <!-- Submitted documents will be listed here -->
                <?php include './../php_code/fetch_outline_documents.php'; ?>
            </div>
        </div>
    </div>

    <!-- Modal for Editing Document -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" enctype="multipart/form-data">
                        <input type="hidden" id="editId" name="id">
                        <div class="mb-3">
                            <label for="editTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" id="editTitle" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="editAcademicYear" class="form-label">Academic Year</label>
                            <select id="editAcademicYear" name="academic_year" class="form-select">
                                <option value="" disabled selected hidden>ปีการศึกษา</option>
                                <option>2024</option>
                                <option>2025</option>
                                <option>2026</option>
                                <option>2027</option>
                                <option>2028</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editTerm" class="form-label">Term</label>
                            <select id="editTerm" name="term" class="form-select">
                                <option value="" disabled selected hidden>เทอม</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="summer">summer</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="editDescription" name="description" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editCapacity" class="form-label">Capacity</label>
                            <input type="number" class="form-control" id="editCapacity" name="capacity" required>
                        </div>
                        <div class="mb-3">
                            <label for="editResponsible" class="form-label">Responsible</label>
                            <input type="text" class="form-control" id="editResponsible" name="responsible" required>
                        </div>
                        <div class="mb-3">
                            <label for="editPhone" class="form-label">Phone</label>
                            <input type="tel" class="form-control" id="editPhone" name="phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="editFile" class="form-label">Upload Files</label>
                            <input type="file" class="form-control" id="editFile" name="files[]" multiple>
                            <div id="fileList"></div> <!-- To display file names -->
                        </div>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
            // จัดการกับการส่งแบบฟอร์มสำหรับแก้ไขเอกสาร
            $('#editForm').on('submit', function(e) {
                e.preventDefault(); // ป้องกันการส่งแบบฟอร์มเดิม

                Swal.fire({
                    title: 'คุณแน่ใจหรือไม่?',
                    text: 'คุณต้องการที่จะบันทึกการเปลี่ยนแปลงในเอกสารนี้หรือไม่?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ใช่, บันทึก!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var formData = new FormData(this);

                        $.ajax({
                            url: './../php_code/update_document.php', // อัปเดตสคริปต์ PHP ของคุณสำหรับการจัดการการแก้ไข
                            type: 'POST',
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function(response) {
                                Swal.fire({
                                    title: 'สำเร็จ',
                                    text: 'เอกสารได้รับการอัปเดตเรียบร้อยแล้ว',
                                    icon: 'success',
                                    confirmButtonText: 'ตกลง'
                                }).then(function() {
                                    location.reload();
                                });
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    title: 'ข้อผิดพลาด',
                                    text: 'มีข้อผิดพลาดในการอัปเดตเอกสาร',
                                    icon: 'error',
                                    confirmButtonText: 'ตกลง'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
</footer>

</html>