<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            color: #fff;
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
            max-width: 400px;
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
            background-color: #6a11cb;
            color: white;
            padding: 10px 0;
            text-align: center;
            width: 100%;
            position: fixed;
            bottom: 0;
        }
    </style>
</head>
<body>

<div class="header">
    <h2>Welcome to E-doms</h2>
    <p>Your document management solution</p>
</div>
<div class="login-container">
    <h3>Login</h3>
    <form action="login.php" method="post">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Login</button>
    </form>
</div>

<footer>
    &copy; 2024 SUB IT Team. All rights reserved.
</footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
