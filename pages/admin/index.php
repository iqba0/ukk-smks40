<?php
session_start();
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;

include_once '../../db/db_config.php';

// Tambah DATA atau Create
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tambah'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    
    $query = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')";
    $result = mysqli_query($conn, $query);
    if ($result) {
        header("Location: $_SERVER[PHP_SELF]");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Edit atau Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    
    $query = "UPDATE users SET username='$username', password='$password', role='$role' WHERE id='$id'";
    $result = mysqli_query($conn, $query);
    if ($result) {
        header("Location: $_SERVER[PHP_SELF]");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Hapus DATA
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    
    $query = "DELETE FROM users WHERE id='$id'";
    $result = mysqli_query($conn, $query);
    if ($result) {
        header("Location: $_SERVER[PHP_SELF]");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Ambil semua data atau READ data
$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);
$rows = [];
while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
}

// Data untuk mode edit
$edit_username = '';
$edit_password = '';
$edit_role = '';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM users WHERE id='$id'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $edit_username = $row['username'];
    $edit_password = $row['password'];
    $edit_role = $row['role'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style/style.css">
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
    <div class="container-fluid">
        <div class="sidebar">
            <div class="sidebar-header">
                <h3>Dashboard</h3>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item <?php echo ($role === 'admin') ? '' : 'd-none'; ?>">
                    <a class="nav-link" href="index.php">Kelola Akun</a>
                </li>
                <li class="nav-item <?php echo ($role === 'admin' || $role === 'owner') ? '' : 'd-none'; ?>">
                    <a class="nav-link" href="../activity/log_activity.php">Log Activity</a>
                </li>
                <li class="nav-item <?php echo ($role === 'admin' || $role === 'kasir') ? '' : 'd-none'; ?>">
                    <a class="nav-link" href="../transaksi/">Transaksi</a>
                </li>
                <li class="nav-item <?php echo ($role === 'admin') ? '' : 'd-none'; ?>">
                    <a class="nav-link" href="../product/">Data Produk</a>
                </li>
            </ul>
            <ul class="nav flex-column mt-auto">
                <li class="nav-item">
                    <a class="nav-link logout-link" href="../../auth/logout.php">Keluar</a>
                </li>
            </ul>
        </div>
        <div class="content">
            <div class="container">
                <div class="form-container">
                    <h2 class="mb-4"><?php echo isset($_GET['id']) ? 'Edit Akun' : 'Tambah Akun'; ?></h2>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <?php if (isset($_GET['id'])) : ?>
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <?php endif; ?>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username:</label>
                            <input type="text" name="username" class="form-control" value="<?php echo $edit_username; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password:</label>
                            <input type="password" name="password" class="form-control" value="<?php echo $edit_password; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role:</label>
                            <select name="role" class="form-select" required>
                                <option value="kasir" <?php echo ($edit_role === 'kasir') ? 'selected' : ''; ?>>Kasir</option>
                                <option value="admin" <?php echo ($edit_role === 'admin') ? 'selected' : ''; ?>>Admin</option>
                                <option value="owner" <?php echo ($edit_role === 'owner') ? 'selected' : ''; ?>>Owner</option>
                            </select>
                        </div>
                        <?php if (isset($_GET['id'])) : ?>
                            <button type="submit" name="edit" class="btn btn-edit-kasir">Simpan</button>
                            <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-batal-edit-kasir">Batal Edit</a>
                        <?php else : ?>
                            <button type="submit" name="tambah" class="btn btn-tambah-kasir">Simpan</button>
                        <?php endif; ?>
                    </form>
                </div>
                <div class="table-container">
                    <h2 class="mt-5">Data Kasir</h2>
                    <input type="text" id="searchInput" class="form-control mb-3" placeholder="Cari...">
                    <table class="table table-striped mt-3">
                        <thead>
                            <tr>
                                <th scope="col">Username</th>
                                <th scope="col">Password</th>
                                <th scope="col">Role</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rows as $row) : ?>
                                <tr>
                                    <td><?php echo $row['username']; ?></td>
                                    <td><?php echo $row['password']; ?></td>
                                    <td><?php echo $row['role']; ?></td>
                                    <td>
                                        <a href="<?php echo $_SERVER['PHP_SELF'] . '?id=' . $row['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                        <a href="<?php echo $_SERVER['PHP_SELF'] . '?hapus=' . $row['id']; ?>" class="btn btn-sm btn-danger">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const tableRows = document.querySelectorAll('.table tbody tr');

        searchInput.addEventListener('input', function () {
            const searchTerm = this.value.trim().toLowerCase();

            tableRows.forEach(row => {
                const username = row.cells[0].textContent.trim().toLowerCase();
                const password = row.cells[1].textContent.trim().toLowerCase();
                const role = row.cells[2].textContent.trim().toLowerCase();

                if (username.includes(searchTerm) || password.includes(searchTerm) || role.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>
