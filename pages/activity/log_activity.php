<?php
session_start();
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;

if (!$role) {
    header("Location: ../");
    exit();
}

// Koneksi ke database
include '../../db/db_config.php';

// Inisialisasi variabel untuk sorting
$sort_order = isset($_GET['order']) ? $_GET['order'] : 'DESC';
$sort_icon = ($sort_order == 'DESC') ? '▼' : '▲';

// Variabel untuk rentang tanggal yang dipilih
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Query untuk mengambil data transaksi dengan sorting berdasarkan tanggal transaksi
$query = "SELECT transaksi.id, transaksi.tanggal_pembuatan, GROUP_CONCAT(transaksi_produk.nama_produk) AS nama_barang, transaksi.total_harga, transaksi.uang_pelanggan, transaksi.kembalian 
          FROM transaksi 
          JOIN transaksi_produk ON transaksi.id = transaksi_produk.id_transaksi";

// Filter berdasarkan rentang tanggal yang dipilih
if (!empty($start_date) && !empty($end_date)) {
    $query .= " WHERE transaksi.tanggal_pembuatan BETWEEN '$start_date' AND '$end_date'";
}

$query .= " GROUP BY transaksi.id
            ORDER BY transaksi.tanggal_pembuatan $sort_order"; // Sorting berdasarkan tanggal pembuatan

$result = mysqli_query($conn, $query);
if (!$result) {
    die("Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Aktivitas</title>
    <link rel="shortcut icon" type="image/x-icon" href="../../assets/img/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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
                    <a class="nav-link" href="../admin/">Kelola Akun</a>
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

        <!-- Konten dari halaman log activity -->
        <div class="content">
            <h2 class="mt-5">Log Aktivitas Transaksi</h2>
            <div class="text-end mb-3">
                <a href="?order=<?php echo ($sort_order == 'DESC') ? 'ASC' : 'DESC'; ?>" class="btn btn-secondary">
                    Urutkan Tanggal <?php echo $sort_icon; ?>
                </a>
                <a href="cetak_log_activity.php?start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>" class="btn btn-primary">
                    Cetak Log Aktivitas
                </a>
            </div>
            <form method="GET">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <input type="date" class="form-control" name="start_date" value="<?php echo $start_date; ?>">
                    </div>
                    <div class="col-md-3">
                        <input type="date" class="form-control" name="end_date" value="<?php echo $end_date; ?>">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Tanggal Transaksi</th>
                        <th scope="col">Nama Barang</th>
                        <th scope="col">Total Harga</th>
                        <th scope="col">Uang Pelanggan</th>
                        <th scope="col">Kembalian</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr>
                            <td><?php echo $row['tanggal_pembuatan']; ?></td>
                            <td><?php echo $row['nama_barang']; ?></td>
                            <td><?php echo $row['total_harga']; ?></td>
                            <td><?php echo $row['uang_pelanggan']; ?></td>
                            <td><?php echo $row['kembalian']; ?></td>
                            <td>
                                <form action="hapus_log.php" method="post">
                                    <input type="hidden" name="log_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn btn-danger">Hapus Log</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

<?php
// Tutup koneksi database
mysqli_close($conn);
?>
