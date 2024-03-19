
<?php
session_start();
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
if (!$role) {
    header("Location: ../../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="shortcut icon" type="image/x-icon" href="../../assets/img/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/style/sidebar.css">
</head>
<body>
<?php include '../sidebar.php'; ?>
    <div class="content">
        <h1>Selamat Datang Di Aplikasi UPS SMK SUMATRA 40</h1>
    </div>
</body>
</html>
