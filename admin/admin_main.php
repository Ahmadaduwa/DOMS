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
    <title>Admin Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>

<body>
    <?php require('./components/header.php'); ?>

    <div class="container mt-4">
        <div class="form">
            <form class="form-inline mb-3" action="" method="get">
                <div class="form-group">
                    <div class="btn-group ml-2" role="group" aria-label="User Type">
                        <?php 
                        $userTypes = ['sub1', 'sub2', 'sub3', 'sub4', 'sub5', 'sub6'];
                        foreach ($userTypes as $type) : ?>
                            <input type="radio" name="user_type" id="<?php echo $type; ?>" value="<?php echo $type; ?>" class="btn-check" autocomplete="off" <?php if ($userType == $type) echo 'checked'; ?>>
                            <label class="btn btn-outline-primary" for="<?php echo $type; ?>"><?php echo $type; ?></label>
                        <?php endforeach; ?>
                    </div>
                    <button type="submit" class="btn btn-primary ml-2">Search</button>
                </div>
            </form>
        </div>
    </div>

    <div class="container">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Number</th>
                    <th>Documents</th>
                    <th>Files</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['number']); ?></td>
                        <td>
                            <?php
                            $stmt = $conn->prepare("SELECT responsible FROM documents WHERE owner = ?");
                            $stmt->bind_param("s", $user['number']);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $documents = $result->fetch_all(MYSQLI_ASSOC);
                            $stmt->close();

                            foreach ($documents as $document) {
                                echo htmlspecialchars($document['responsible']) . '<br>';
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            $stmt = $conn->prepare("SELECT file_path FROM files WHERE document_id = ?");
                            $stmt->bind_param("s", $user['number']);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $files = $result->fetch_all(MYSQLI_ASSOC);
                            $stmt->close();

                            foreach ($files as $file) {
                                echo htmlspecialchars($file['file_path']) . '<br>';
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>