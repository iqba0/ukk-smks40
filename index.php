<?php
session_start();

include 'db/db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // MD5-kan password

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND password=?");
    $stmt->bind_param("ss", $username, $password); // Ganti parameter kedua menjadi password yang sudah di-MD5
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $row['role'];
        header("Location: pages/dashboard");
        exit();
    } else {
        $loginError = "Invalid username or password";
    }

    $stmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/logo.png">
    <link rel="stylesheet" href="style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
     
    body {
        background-color: #f8f9fa;
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        background-image: url('assets/img/bg.jpg');
    }
    .background-image {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('assets/img/bg.jpg');
            background-size: cover;
            filter: blur(15px); /* Efek blur */
            -webkit-filter: blur(5px); /* Untuk kompatibilitas dengan browser Safari */
            z-index: -1; /* Agar latar belakang gambar berada di belakang elemen lain */
        }
    .login-container {
        margin-top: 100px;
    }
    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        opacity: 0.9;
    }
    .card-header {
        background-color: #c9c4c3;
        color: #fff;
        border-radius: 15px 15px 0 0;
    }
    .logo {
        text-align: center;
        margin-bottom: 20px;
    }
    .logo img {
        width: 150px;
        height: auto;
    }
    .btn-outline-orange {
        color: orange;
        border-color: orange;
    }

    .btn-outline-orange:hover {
        color: white;
        background-color: orange;
    }
</style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center login-container">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="logo">
                            <img src="assets/img/logo.png" alt="Logo">
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (isset($loginError)) : ?>
                        <div id="loginAlert" class="alert alert-danger" role="alert">
                            <?php echo $loginError; ?>
                        </div>
                        <?php endif; ?>
                        <form id="loginForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <button type="button" class="btn btn-outline-secondary" id="togglePassword">Show</button>
                                </div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-outline-orange">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        $("#togglePassword").click(function() {
            var passwordInput = $("#password");
            if (passwordInput.attr("type") === "password") {
                passwordInput.attr("type", "text");
                $(this).text("Hide");
            } else {
                passwordInput.attr("type", "password");
                $(this).text("Show");
            }
        });
    });
    </script>
</body>
</html>