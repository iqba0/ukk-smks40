<?php include 'admin.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="shortcut icon" type="image/x-icon" href="../../assets/img/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/style/sidebar.css">
    <link rel="stylesheet" href="../../assets/style/admin.css">
</head>
<body>
   
    <div class="container-fluid">
        <?php include '../sidebar.php'; ?>
        <div class="content">
        <?php if ($error_message): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>
            <div class="container">
                <div class="form-container">
                    <h2 class="mb-4"><?php echo isset($_GET['id']) ? 'Edit Akun' : 'Tambah Akun'; ?></h2>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <?php if (isset($_GET['id'])) : ?>
                            <input type="hidden" name="ID" value="<?php echo $id; ?>">
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
                            <label for="nama" class="form-label">Nama:</label>
                            <input type="text" name="nama" class="form-control" value="<?php echo $edit_nama; ?>" required>
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
                                <th scope="col">Nama</th>
                                <th scope="col">Role</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rows as $row) : ?>
                                <tr>
                                    <td><?php echo $row['username']; ?></td>
                                    <td><?php echo $row['password']; ?></td>
                                    <td><?php echo $row['nama']; ?></td>
                                    <td><?php echo $row['role']; ?></td>
                                    <td>
                                        <a href="<?php echo $_SERVER['PHP_SELF'] . '?id=' . $row['ID']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                        <a href="<?php echo $_SERVER['PHP_SELF'] . '?hapus=' . $row['ID']; ?>" class="btn btn-sm btn-danger">Hapus</a>
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
                const nama = row.cells[2].textContent.trim().toLowerCase();
                const role = row.cells[3].textContent.trim().toLowerCase();

                if (username.includes(searchTerm) || password.includes(searchTerm) || nama.includes(searchTerm) || role.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>
