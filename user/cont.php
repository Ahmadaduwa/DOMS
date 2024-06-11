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
            padding-top: 45px;
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

        .team-members {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .member {
            text-align: center;
            margin: 20px;
            width: 284px;
            border: 1px solid #e0e0e0;
            padding: 20px;
            border-radius: 10px;
            background-color: #fff;
            transition: transform 0.3s ease-in-out;
        }

        .member:hover {
            transform: scale(1.05);
        }

        .member img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .footer {
            background-color: #007bff;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            width: 100%;
            bottom: 0;
        }

        .container {
            margin-top: 5px;
            margin-bottom: 5px;
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

    <div class="container mt-5 pt-5">
        <div id="contactForms" class="section active">
            <h2 class="text-center">Contact</h2>
            <div class="team-info text-center">
                <h3>SUB IT 2024 Team</h3>
                <p>Meet the amazing team behind this website:</p>
                <div class="team-members">
                    <div class="member">
                        <img src="./../image/wa.jpg" alt="John Doe">
                        <h4>Ahmadaduwa Da-oh</h4>
                        <p>Full-stack Developer</p>
                        <p>Email: <br>adu27747g@gmail.com</p>
                        <p>Phone: 092-370-7727</p>
                    </div>
                    <div class="member">
                        <img src="./../image/tin.jpg" alt="Jane Smith">
                        <h4>Tachanon Srisook</h4>
                        <p>Frontend Developer</p>
                        <p>Email: tachanon.sr@mail.wu.ac.th</p>
                        <p>Phone: 083-177-5513</p>
                    </div>
                    <div class="member">
                        <img src="./../image/van.jpg" alt="Michael Johnson">
                        <h4>Audsadawut Nakthungtao</h4>
                        <p>Backend Developer</p>
                        <p>Email: audsadawut.na@mail.wu.ac.th</p>
                        <p>Phone: 094-358-6014</p>
                    </div>
                    <div class="member">
                        <img src="./../image/teen.jpg" alt="Emily Davis">
                        <h4>Teerapat Jongjit</h4>
                        <p>UI/UX Designer</p>
                        <p>Email: <br>Info.teen1301@gmail.com</p>
                        <p>Phone: 098-434-8604</p>
                    </div>
                </div>
            </div>
            <div class="team-name text-center mt-4">
                <p>"Our team comprises talented professionals with diverse expertise, collaborating to develop innovative and impactful solutions. We are dedicated to delivering top-notch products and services to our clients."</p>
            </div>
        </div>
    </div>

    <footer class="footer">
        <span>&copy; 2024 SUB IT Team. All rights reserved.</span>
    </footer>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- Include SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Include custom JS -->
    <script src="./scripts.js"></script>
</body>

</html>