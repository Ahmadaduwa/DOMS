<?php 
    require('helper/check_user.php');
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
        <!-- Proposal Status Section -->
        <div id="statusDocument" class="section active">
            <h2>Proposal Status</h2>
            <div id="statusDocs">
                <!-- Submitted documents will be listed here -->
                <?php include './../php_code/fetch_status_documents.php'; ?>
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
</footer>

</html>