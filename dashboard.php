<?php
session_start();
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;

if (!$role) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style/style.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .container-fluid {
            padding-left: 0;
            padding-right: 0;
            overflow-x: hidden;
        }
        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            padding-top: 3.5rem;
            background-color: #343a40;
            color: #fff;
            z-index: 1;
            overflow-y: auto;
        }
        .sidebar .nav-link {
            padding: 10px 20px;
            color: #fff;
        }
        .sidebar .nav-link:hover {
            background-color: #495057;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
        }
        .sidebar-header {
            background-color: #212529;
            padding: 20px;
            text-align: center;
        }
        .sidebar-header h3 {
            margin-bottom: 0;
            color: #fff;
        }
        .nav-item {
            margin-bottom: 10px;
        }
        .nav-link {
            color: #fff !important;
            font-weight: bold;
        }
        .nav-link:hover {
            color: #f8f9fa !important;
        }
        .logout-link {
            color: #dc3545 !important;
        }
        .logout-link:hover {
            color: #f8d7da !important;
        }
        .btn-tambah-kasir {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-tambah-kasir:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        .btn-edit-kasir {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-edit-kasir:hover {
            background-color: #0069d9;
            border-color: #0062cc;
        }
        .btn-batal-edit-kasir {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-batal-edit-kasir:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
        .mb-4 {
    background-color: orangered;
    color: #fff;
    padding: 10px;
}

    </style>
</head>
<body>
<div class="sidebar">
            <div class="sidebar-header">
                <h3>Dashboard</h3>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item <?php echo ($role === 'admin') ? '' : 'd-none'; ?>">
                    <a class="nav-link" href="pages/admin/">Kelola Akun</a>
                </li>
                <li class="nav-item <?php echo ($role === 'admin' || $role === 'owner') ? '' : 'd-none'; ?>">
                    <a class="nav-link" href="pages/activity/log_activity.php">Log Activity</a>
                </li>
                <li class="nav-item <?php echo ($role === 'admin' || $role === 'kasir') ? '' : 'd-none'; ?>">
                    <a class="nav-link" href="pages/transaksi/">Transaksi</a>
                </li>
                <li class="nav-item <?php echo ($role === 'admin') ? '' : 'd-none'; ?>">
                    <a class="nav-link" href="pages/product/">Data Produk</a>
                </li>
            </ul>
            <ul class="nav flex-column mt-auto">
                <li class="nav-item">
                    <a class="nav-link logout-link" href="auth/logout.php">Keluar</a>
                </li>
            </ul>
        </div>
    <div class="content">
        <h1>Selamat Datang Di Aplikasi UPS SMK SUMATRA 40</h1>
    </div>
</body>
</html>
