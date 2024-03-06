<?php
session_start();
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;

include_once '../../db/db_config.php';

// Tambah DATA atau Create
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tambah'])) {
    $nama_produk = $_POST['nama_produk'];
    // Hilangkan tanda titik dari input harga sebelum menyimpannya
    $harga_produk = str_replace('.', '', $_POST['harga_produk']);
    $jumlah = $_POST['jumlah'];
    $kode_unik = $_POST['kode_unik'];
    
    $query = "INSERT INTO products (nama_produk, harga_produk, created_at, updated_at, jumlah, kode_unik) VALUES ('$nama_produk', $harga_produk, NOW(), NOW(), $jumlah, '$kode_unik')";
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
    $nama_produk = $_POST['nama_produk'];
    // Hilangkan tanda titik dari input harga sebelum menyimpannya
    $harga_produk = str_replace('.', '', $_POST['harga_produk']);
    $jumlah = $_POST['jumlah'];
    $kode_unik = $_POST['kode_unik'];
    
    $query = "UPDATE products SET nama_produk='$nama_produk', harga_produk=$harga_produk, updated_at=NOW(), jumlah=$jumlah, kode_unik='$kode_unik' WHERE id=$id";
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
    
    $query = "DELETE FROM products WHERE id=$id";
    $result = mysqli_query($conn, $query);
    if ($result) {
        header("Location: $_SERVER[PHP_SELF]");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Ambil semua data atau READ data
$query = "SELECT * FROM products";
$result = mysqli_query($conn, $query);
$rows = [];
while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
}

// Data untuk mode edit
$edit_nama_produk = '';
$edit_harga_produk = '';
$edit_jumlah = '';
$edit_kode_unik = '';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM products WHERE id=$id";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $edit_nama_produk = $row['nama_produk'];
    // Menggunakan number_format untuk mengatur format harga_produk tanpa desimal .00
    $edit_harga_produk = number_format($row['harga_produk'], 0, ',', '');
    $edit_jumlah = $row['jumlah'];
    $edit_kode_unik = $row['kode_unik'];
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
        .btn-tambah-produk {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-tambah-produk:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        .btn-edit-produk {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-edit-produk:hover {
            background-color: #0069d9;
            border-color: #0062cc;
        }
        .btn-batal-edit-produk {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-batal-edit-produk:hover {
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
                    <a class="nav-link" href="../admin">Kelola Akun</a>
                </li>
                <li class="nav-item <?php echo ($role === 'admin' || $role === 'owner') ? '' : 'd-none'; ?>">
                    <a class="nav-link" href="../activity/log_activity.php">Log Activity</a>
                </li>
                <li class="nav-item <?php echo ($role === 'admin' || $role === 'kasir') ? '' : 'd-none'; ?>">
                    <a class="nav-link" href="../transaksi/">Transaksi</a>
                </li>
                <li class="nav-item <?php echo ($role === 'admin') ? '' : 'd-none'; ?>">
                    <a class="nav-link" href="index.php">Data Produk</a>
                </li>
            </ul>
            <ul class="nav flex-column mt-auto">
                <li class="nav-item">
                    <a class="nav-link logout-link" href="../../auth/logout.php">Logout</a>
                </li>
            </ul>
        </div>
        <div class="content">
            <div class="container">
                <div class="form-container">
                    <h2 class="mb-4"><?php echo isset($_GET['id']) ? 'Edit Produk' : 'Tambah Produk'; ?></h2>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <?php if (isset($_GET['id'])) : ?>
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <?php endif; ?>
                        <div class="mb-3">
                            <label for="nama_produk" class="form-label">Nama Produk:</label>
                            <input type="text" name="nama_produk" class="form-control" value="<?php echo $edit_nama_produk; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="harga_produk" class="form-label">Harga Produk:</label>
                            <input type="text" id="inputHarga" name="harga_produk" class="form-control" value="<?php echo $edit_harga_produk; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah" class="form-label">Jumlah:</label>
                            <input type="number" name="jumlah" class="form-control" value="<?php echo $edit_jumlah; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="kode_unik" class="form-label">Kode Unik:</label>
                            <input type="text" name="kode_unik" class="form-control" value="<?php echo $edit_kode_unik; ?>" required>
                        </div>
                        <?php if (isset($_GET['id'])) : ?>
                            <button type="submit" name="edit" class="btn btn-edit-produk">Simpan</button>
                            <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-batal-edit-produk">Batal Edit</a>
                        <?php else : ?>
                            <button type="submit" name="tambah" class="btn btn-tambah-produk">Simpan</button>
                        <?php endif; ?>
                    </form>
                </div>
                <div class="table-container">
                    <h2 class="mt-5">Data Produk</h2>
                    <table class="table table-striped mt-3">
                        <thead>
                            <tr>
                                <th scope="col">Nama Produk</th>
                                <th scope="col">Harga Produk</th>
                                <th scope="col">Jumlah</th>
                                <th scope="col">Kode Unik</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rows as $row) : ?>
                                <tr>
                                    <td><?php echo $row['nama_produk']; ?></td>
                                    <!-- Tampilkan harga dengan pemisah ribuan -->
                                    <td>Rp <?php echo number_format($row['harga_produk'], 0, ',', '.'); ?></td>
                                    <td><?php echo $row['jumlah']; ?></td>
                                    <td><?php echo $row['kode_unik']; ?></td>
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

    <script>
        // Mendapatkan input harga
        var inputHarga = document.getElementById('inputHarga');

        // Menambahkan event listener untuk input
        inputHarga.addEventListener('input', function(event) {
            // Mengambil nilai yang dimasukkan pengguna
            var nilai = event.target.value;

            // Menghapus titik dan koma dari nilai untuk memastikan bahwa kita menghapusnya terlebih dahulu sebelum menambahkan yang baru
            nilai = nilai.replace(/\./g, '').replace(/,/g, '');

            // Menambahkan titik setiap tiga digit dari kanan
            nilai = nilai.replace(/\B(?=(\d{3})+(?!\d))/g, ".");

            // Menetapkan nilai yang telah diformat kembali ke input
            event.target.value = nilai;
        });
    </script>
</body>
</html>
