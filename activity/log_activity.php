<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$role = $_SESSION['role'];
if ($role !== 'admin' && $role !== 'kasir') {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Log Activity</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="content-container">
        <h2>Create Log Activity</h2>
        <!-- Isi Form Log Activity -->
    </div>
</body>
</html>
