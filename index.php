<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - เว็ปไซต์ส่งเอกสาร</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./components/footerlogin/footer.css">
    <link rel="icon" href="./image/SAB Logo-03.png">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap');

        body {
            background: linear-gradient(135deg, #790D7B 0%, #790D7B 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            font-family: 'Kanit', sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            color: white;
            animation: fadeIn 2s ease-in-out;
        }
        .header h2 {
            font-size: 3rem;
            margin: 0;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }
        .header p {
            font-size: 1.25rem;
            margin-top: 10px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .login-container {
            width: 100%;
            max-width: 600px;
            padding: 30px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            animation: slideIn 1s ease-in-out;
        }
        @keyframes slideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .login-container h3 {
            margin-bottom: 30px;
            font-weight: bold;
            color: #333;
            text-align: center;
        }
        .login-container .form-control {
            border-radius: 25px;
            padding: 20px;
            font-size: 16px;
        }
        .login-container .btn {
            border-radius: 25px;
            padding: 10px;
            font-size: 18px;
            background: #6a11cb;
            border: none;
            color: white;
            transition: background 0.3s;
        }
        .login-container .btn:hover {
            background: #5c0fb8;
        }
        .login-container .form-footer {
            text-align: center;
            margin-top: 20px;
        }
        .login-container .form-footer a {
            color: #6a11cb;
            text-decoration: none;
        }
        .login-container .form-footer a:hover {
            text-decoration: underline;
        }
        footer {
            color: white;
        }
        .gold-text {
            color: gold;
        }
    </style>
</head>
<body>

<div class="header">
    <img src="./image/SAB Logo-03.png" alt="SAB Logo" width="100px">
    <h2 class="gold-text">Welcome to E-doms</h2>
    <p>Your document management solution</p>
</div>
<div class="login-container">
    <h3>Login</h3>
    <form action="login.php" method="post">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Username" required >
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Login</button>
    </form>
</div>
<?php
    require('./components/footerlogin/footer.php');
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
