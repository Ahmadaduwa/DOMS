<?php
    require('helper/check_ohm.php');
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
                        <a class="nav-link" href="./approved.php" data-target="Approved">Approved</a>
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
        <div id="home" class="section active">
            <h2>Approval Document</h2>
            <div id="approvalDocs">
                <!-- Submitted documents will be listed here -->
                <?php include './../php_code/fetch_approval_documents.php'; ?>
            </div>
        </div>
    </div>
    
    <!-- Comment Modal -->
    <div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="commentModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="commentModalLabel">Add Comment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="commentForm">
                        <div class="form-group">
                            <label for="commentText">Comment</label>
                            <textarea class="form-control" id="commentText" name="commentText" rows="3"></textarea>
                        </div>
                        <input type="hidden" id="documentId" name="documentId">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveComment">Save Comment</button>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="./scripts.js"></script>
    <!--
        // Event handler for comment button click
            $(document).on('click', '.comment-btn', function() {
                var documentId = $(this).data('document-id'); // Get the document ID from data attribute
                $('#documentId').val(documentId); // Set the document ID in the hidden input field
                $('#commentModal').modal('show'); // Show the modal
            });

            // Event handler for save comment button click
            $('#saveComment').on('click', function() {
                var formData = $('#commentForm').serialize();

                // Show confirmation dialog before saving
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to save this comment?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, save it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post('./../php_code/save_comment.php', formData, function(response) {
                            // Handle the response from the server
                            Swal.fire({
                                title: 'Success',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                $('#commentModal').modal('hide'); // Hide the modal after saving
                                location.reload(); // Reload the page to show the new comment
                            });
                        }, 'json');
                    }
                });
            });
    -->
    <script>
        $(document).ready(function() {
            // Event handler for approving document button click
            $(document).on("click", ".approve-btn", function() {
                var documentId = $(this).data('id');
                // Prompt for confirmation using SweetAlert2
                Swal.fire({
                    title: 'ยืนยันการอนุมัติ',
                    text: "คุณแน่ใจหรือไม่ที่ต้องการอนุมัติเอกสารนี้?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#dc3545',
                    confirmButtonText: 'ยืนยัน',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // If confirmed, send AJAX request to approve document
                        $.ajax({
                            url: './../php_code/approve_document.php',
                            type: 'POST',
                            data: JSON.stringify({
                                id: documentId
                            }),
                            contentType: 'application/json',
                            success: function(response) {
                                var res = JSON.parse(response);
                                if (res.success) {
                                    Swal.fire({
                                        title: 'สำเร็จ',
                                        text: 'เอกสารได้รับการอนุมัติเรียบร้อยแล้ว',
                                        icon: 'success'
                                    }).then(() => {
                                        location.reload(); // Reload the page after approving
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'ข้อผิดพลาด',
                                        text: 'เกิดข้อผิดพลาด: ' + res.error,
                                        icon: 'error'
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    title: 'ข้อผิดพลาด',
                                    text: 'เกิดข้อผิดพลาดในการส่งคำร้องขอ: ' + error,
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });
            });

            // Event handler for comment button click
            $(document).on("click", ".comment-btn", function() {
                var documentId = $(this).data("document-id");
                Swal.fire({
                    title: "เพิ่มความคิดเห็น",
                    input: "textarea",
                    inputLabel: "ความคิดเห็น",
                    inputPlaceholder: "กรอกความคิดเห็นที่นี่...",
                    showCancelButton: true,
                    cancelButtonText: "ยกเลิก",
                    confirmButtonText: "บันทึก",
                    showLoaderOnConfirm: true,
                    preConfirm: (comment) => {
                        return new Promise((resolve) => {
                            // Send AJAX request to save comment
                            $.ajax({
                                url: "./../php_code/save_comment.php",
                                type: "POST",
                                data: {
                                    documentId: documentId,
                                    commentText: comment
                                },
                                success: function(response) {
                                    resolve();
                                },
                                error: function(xhr, status, error) {
                                    Swal.showValidationMessage("เกิดข้อผิดพลาดในการส่งคำร้องขอ: " + error);
                                },
                            });
                        });
                    },
                    allowOutsideClick: () => !Swal.isLoading(),
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: "สำเร็จ",
                            text: "ความคิดเห็นได้รับการบันทึกเรียบร้อยแล้ว",
                            icon: "success",
                        }).then(() => {
                            location.reload(); // Reload the page after saving comment
                        });
                    }
                });
            });

            $(document).on("click", ".return-btn", function() {
                var documentId = $(this).data("id"); // Get the document ID from data attribute

                // Fetch users with higher numbers than current user
                $.post('./../php_code/fetch_users.php', {
                    documentId: documentId
                }, function(response) {
                    console.log(response); // Log response for debugging

                    if (response.error) {
                        Swal.fire({
                            title: 'Error',
                            text: 'เกิดข้อผิดพลาด: ' + response.error + ' (session: ' + JSON.stringify(response.session) + ', post: ' + JSON.stringify(response.post) + ')',
                            icon: 'error',
                        });
                        return;
                    }

                    if (response.length === 0) {
                        Swal.fire({
                            title: 'Error',
                            text: 'ไม่พบผู้ใช้ที่มีหมายเลขสูงกว่า',
                            icon: 'error',
                        });
                        return;
                    }

                    var userOptions = {};
                    response.forEach(function(user) {
                        userOptions[user.number] = user.name; // Change user.id to user.number
                    });

                    Swal.fire({
                        title: 'เลือกผู้ใช้เพื่อส่งกลับ',
                        input: 'select',
                        inputOptions: userOptions,
                        inputPlaceholder: 'เลือกผู้ใช้',
                        showCancelButton: true,
                        cancelButtonText: "ยกเลิก",
                        confirmButtonText: "ส่งกลับ",
                        preConfirm: (selectedUserNumber) => { // Change parameter name
                            return new Promise((resolve) => {
                                // Send AJAX request to return document to selected user
                                $.ajax({
                                    url: "./../php_code/return_document.php",
                                    type: "POST",
                                    data: {
                                        documentId: documentId,
                                        userNumber: selectedUserNumber // Change userId to userNumber
                                    },
                                    success: function(response) {
                                        console.log(response); // Log response for debugging
                                        resolve(); // Resolve promise without validation
                                    },
                                    error: function(xhr, status, error) {
                                        Swal.showValidationMessage("เกิดข้อผิดพลาดในการส่งคำร้องขอ: " + error);
                                    },
                                });
                            });
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: "สำเร็จ",
                                text: "ส่งเอกสารกลับเรียบร้อยแล้ว",
                                icon: "success",
                            }).then(() => {
                                location.reload(); // Reload the page after returning document
                            });
                        }
                    });
                }, 'json').fail(function(xhr, status, error) {
                    Swal.fire({
                        title: 'Error',
                        text: 'เกิดข้อผิดพลาดในการส่งคำร้องขอ: ' + error,
                        icon: 'error',
                    });
                });
            });
        });
    </script>
</footer>

</html>