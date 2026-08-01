<?php
session_start();

if (isset($_SESSION['login'])) {
    header("Location: dashboard.php");
    exit;
}

include "config/database.php";

// kode login...
include "config/database.php";

if (isset($_POST['login'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5($_POST['password']);

    $query = mysqli_query(
        $conn,
        "SELECT * FROM users
        WHERE email='$email'
        AND password='$password'"
    );

    if (mysqli_num_rows($query) > 0) {

        $user = mysqli_fetch_assoc($query);

        $_SESSION['login'] = true;
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        header("Location: dashboard.php");
        exit;

    } else {

        $error = "Email atau Password salah!";

    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Inventory Management System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container">

        <div class="row justify-content-center align-items-center vh-100">

            <div class="col-md-4">

                <div class="card shadow">

                    <div class="card-body">

                        <h3 class="text-center mb-4">
                            Inventory System
                        </h3>

                        <?php if (isset($error)) { ?>
                            <div class="alert alert-danger">
                                <?= $error; ?>
                            </div>
                        <?php } ?>

                        <form method="POST">

                            <div class="mb-3">

                                <label>Email</label>

                                <input type="email" name="email" class="form-control" placeholder="Enter email"
                                    required>

                            </div>

                            <div class="mb-3">

                                <label>Password</label>

                                <input type="password" name="password" class="form-control" placeholder="Enter password"
                                    required>

                            </div>

                            <button type="submit" name="login" class="btn btn-primary w-100">
                                Login
                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>