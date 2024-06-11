<!-- <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
    <div class="contaier-fulid ms-2">
        <div class="collapse navbar-collapse text fs-6" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="../php_code/logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav> -->

<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <a href="#" class="navbar-brand">Admin E-Doms</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link logoutBtn" href="../php_code/logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">

    <h1>Welcome to Admin Page!</h1>
    <div class="alert alert-info">
        <strong>Welcome, <?php echo $username; ?>!</strong>
    </div>
    
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td>Id users</td>
                <td><?php echo $username; ?></td>
            </tr>
            <tr>
                <td>Name</td>
                <td><?php echo $name; ?></td>
            </tr>
            <tr>
                <td>Number</td>
                <td><?php echo $number; ?></td>
            </tr>
        </tbody>
    </table> 
</div>