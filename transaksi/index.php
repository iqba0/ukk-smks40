<?php
session_start();
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;

if (!$role) {
    header("Location: ../");
    exit();
}
include_once '../db/db_config.php';

// Fungsi untuk mengambil data produk berdasarkan keyword
function cariProduk($keyword) {
    global $conn;
    $query = "SELECT * FROM products WHERE nama_produk LIKE '%$keyword%' OR harga_produk LIKE '%$keyword%' OR kode_unik LIKE '%$keyword%'";
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

// Fungsi untuk menambahkan produk ke dalam struk dengan jumlah yang ditentukan
function tambahkanProduk($id, $jumlah) {
    global $conn;
    $query = "SELECT * FROM products WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $produk = mysqli_fetch_assoc($result);
    // Validasi jumlah yang dimasukkan tidak melebihi ketersediaan barang
    if ($jumlah > $produk['jumlah']) {
        return false; // Jika melebihi, kembalikan false
    } else {
        $produk['jumlah'] = $jumlah; // Set jumlah produk
        return $produk;
    }
}

// Fungsi untuk mengurangi jumlah produk dari struk
function kurangiProduk($index) {
    $struk = isset($_SESSION['struk']) ? $_SESSION['struk'] : [];
    unset($struk[$index]);
    $_SESSION['struk'] = array_values($struk);
}

// Fungsi untuk mengecek apakah produk sudah ada di struk
function cekProduk($produk, $struk) {
    foreach ($struk as $index => $item) {
        if ($item['id'] == $produk['id']) {
            return $index;
        }
    }
    return -1;
}

$struk = isset($_SESSION['struk']) ? $_SESSION['struk'] : [];
$totalHarga = isset($_SESSION['totalHarga']) ? $_SESSION['totalHarga'] : 0;
$error = '';

// Variabel untuk menampung hasil pencarian produk
$rows = [];

// Jika ada data yang dikirimkan melalui metode POST (untuk pencarian)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['keyword'])) {
    $keyword = $_POST['keyword'];
    $rows = cariProduk($keyword);
} else {
    // Jika tidak ada pencarian, ambil semua data produk
    $query = "SELECT * FROM products";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
}

// Jika tombol "Tambah" ditekan
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id']) && isset($_GET['tambah'])) {
    $id_produk = $_GET['id'];
    $jumlah = isset($_GET['jumlah']) ? $_GET['jumlah'] : 1; // Ambil jumlah yang ditentukan, default 1 jika tidak ada
    $produk = tambahkanProduk($id_produk, $jumlah);
    if ($produk === false) {
        $error = 'Jumlah produk melebihi ketersediaan!';
    } else {
        // Periksa apakah produk sudah ada di struk
        $index = cekProduk($produk, $struk);
        if ($index != -1) {
            // Jika sudah ada, tambahkan jumlahnya
            $struk[$index]['jumlah'] += $jumlah;
        } else {
            // Jika belum ada, tambahkan produk baru ke struk
            array_push($struk, $produk);
        }
        // Hitung kembali total harga
        $totalHarga = 0;
        foreach ($struk as $item) {
            $totalHarga += $item['harga_produk'] * $item['jumlah'];
        }
        $_SESSION['struk'] = $struk;
        $_SESSION['totalHarga'] = $totalHarga;
    }
}

// Jika tombol "Kurang" ditekan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['kurang'])) {
    $index = $_POST['index'];
    kurangiProduk($index);
    header("Location: " . $_SERVER['PHP_SELF']); // Redirect kembali ke halaman ini
}

// Jika tombol "Cetak dan Simpan" ditekan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cetak'])) {
    // Pastikan $uang dan $kembalian_transaksi didefinisikan dan tidak kosong
    if (isset($_POST['uang']) && isset($_POST['kembalian'])) {
        $uang = $_POST['uang'];
        $kembalian_transaksi = $_POST['kembalian'];

        // Simpan transaksi ke dalam tabel 'transaksi' dengan total harga
        $query_transaksi = "INSERT INTO transaksi (uang_pelanggan, kembalian, total_harga) VALUES ($uang, $kembalian_transaksi, $totalHarga)";
        $result_transaksi = mysqli_query($conn, $query_transaksi);
        if (!$result_transaksi) {
            $error = 'Gagal menyimpan transaksi!';
        } else {
            // Ambil ID transaksi terakhir
            $id_transaksi = mysqli_insert_id($conn);

            // Simpan informasi produk ke dalam tabel transaksi_produk
            foreach ($struk as $produk) {
                $nama_produk = $produk['nama_produk'];
                $harga_produk = $produk['harga_produk'];
                $jumlah = $produk['jumlah'];
                $kode_unik = $produk['kode_unik'];
                $totalHargaProduk = $harga_produk * $jumlah;
                
                // Sesuaikan query INSERT untuk memasukkan nilai-nilai yang tepat ke dalam database
                $query_produk = "INSERT INTO transaksi_produk (id_transaksi, nama_produk, harga_produk, jumlah, kode_unik, total_harga) VALUES ($id_transaksi, '$nama_produk', $harga_produk, $jumlah, '$kode_unik', $totalHargaProduk)";
                $result_produk = mysqli_query($conn, $query_produk);
                if (!$result_produk) {
                    $error = 'Gagal menyimpan informasi produk dalam transaksi!';
                    break; // Keluar dari loop jika gagal menyimpan
                }

                // Kurangi jumlah produk dari tabel products
                $query_update_produk = "UPDATE products SET jumlah = jumlah - $jumlah WHERE nama_produk = '$nama_produk'";
                $result_update_produk = mysqli_query($conn, $query_update_produk);
                if (!$result_update_produk) {
                    $error = 'Gagal mengurangi jumlah produk!';
                    break; // Keluar dari loop jika gagal mengurangi jumlah
                }
            }

            // Bersihkan struk dan total harga
            $_SESSION['struk'] = [];
            $_SESSION['totalHarga'] = 0;
            $struk = [];
            $totalHarga = 0;

            // Redirect ke halaman cetak struk dengan menyertakan ID transaksi
            header("Location: cetak_struk.php?id_transaksi=$id_transaksi");
            exit(); // Pastikan tidak ada output sebelum redirection
        }
    } else {
        // Jika $uang atau $kembalian_transaksi kosong, berikan pesan kesalahan
        $error = "Mohon lengkapi informasi uang dan kembalian.";
    }
}

// Jika tombol "Perbarui Transaksi" ditekan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['refresh'])) {
    // Bersihkan struk dan total harga
    $_SESSION['struk'] = [];
    $_SESSION['totalHarga'] = 0;
    $struk = [];
    $totalHarga = 0;
    // Redirect untuk merefresh halaman
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Kasir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style/style.css">

    <!-- CSS khusus untuk pencetakan struk -->
    <style media="print">
        @page {
            size: auto;   /* auto is the current printer page size */
            margin: 0;     /* this affects the margin in the printer settings */
        }

        body {
            margin: 0;
        }

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
        .container {
            padding: 20px;
        }

        /* Sesuaikan dengan selector yang tepat untuk bagian struk */
        .struk-print {
            border: 1px solid black;
            padding: 10px;
        }

        /* Sembunyikan tombol cetak ketika mencetak */
        .no-print {
            display: none;
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
                    <a class="nav-link" href="activity/log_activity.php">Log Activity</a>
                </li>
                <li class="nav-item <?php echo ($role === 'admin' || $role === 'owner' || $role === 'kasir') ? '' : 'd-none'; ?>">
                    <a class="nav-link" href="../transaksi/">Transaksi</a>
                </li>
                <li class="nav-item <?php echo ($role === 'admin') ? '' : 'd-none'; ?>">
                    <a class="nav-link" href="../product/">Data Produk</a>
                </li>
                <li class="nav-item <?php echo ($role === 'owner') ? '' : 'd-none'; ?>">
                    <a class="nav-link" href="owner/laporan.php">Laporan</a>
                </li>
            </ul>
            <ul class="nav flex-column mt-auto">
                <li class="nav-item">
                    <a class="nav-link logout-link" href="../auth/logout.php">Keluar</a>
                </li>
            </ul>
        </div>
        <div class="content">
        <div class="container">
        <h2 class="mt-5">Transaksi Kasir</h2>
        <h4>Total Harga: Rp. <?php echo number_format($totalHarga, 0, ',', '.'); ?></h4>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Cari produk berdasarkan nama, harga, atau kode unik" name="keyword">
                <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Cari</button>
            </div>
        </form>
        <table class="table">
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
    <?php if (!empty($rows)) : ?>
        <?php foreach ($rows as $row) : ?>
            <tr>
                <td><?php echo $row['nama_produk']; ?></td>
                <td>Rp <?php echo number_format($row['harga_produk'], 0, ',', '.'); ?></td>
                <td><?php echo $row['jumlah']; ?></td>
                <td><?php echo $row['kode_unik']; ?></td>
                <td>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <input type="number" name="jumlah" value="1" min="1" class="form-control" style="width: 70px; display: inline-block;">
                        <button type="submit" class="btn btn-sm btn-primary" name="tambah">Tambah</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else : ?>
        <tr>
            <td colspan="5">Tidak ada produk yang ditemukan.</td>
        </tr>
    <?php endif; ?>
</tbody>
        </table>
        <?php if (!empty($struk)) : ?>
        <h4>Struk Harga</h4>
        <table class="table">
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
                <?php foreach ($struk as $index => $produk) : ?>
                <tr>
                    <td><?php echo $produk['nama_produk']; ?></td>
                    <td><?php echo $produk['harga_produk']; ?></td>
                    <td><?php echo $produk['jumlah']; ?></td>
                    <td><?php echo $produk['kode_unik']; ?></td>
                    <td>
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                            <input type="hidden" name="index" value="<?php echo $index; ?>">
                            <button type="submit" name="kurang" class="btn btn-sm btn-danger">Kurang</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="mb-3">
                <label for="uang" class="form-label">Uang Diberikan:</label>
                <input type="text" name="uang" id="uang" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="kembalian" class="form-label">Kembalian:</label>
                <input type="text" name="kembalian" id="kembalian" class="form-control" readonly>
            </div>
            <button type="submit" name="cetak" class="btn btn-success">Cetak dan Simpan</button>
            <button type="submit" name="refresh" class="btn btn-primary">Perbarui Transaksi</button>
        </form>
        <?php endif; ?>
        <?php if (!empty($error)) : ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
    </div>
    </div>
    </div>
    

    <!-- JavaScript untuk mencetak struk -->
    <script>
        function printStruk() {
            window.print();
        }

        // Hitung kembalian saat input uang pelanggan
        document.getElementById('uang').addEventListener('input', function() {
            var uang = parseFloat(this.value);
            var totalHarga = <?php echo $totalHarga; ?>;
            var kembalian = uang - totalHarga;
            document.getElementById('kembalian').value = kembalian >= 0 ? kembalian : 0;
        });
    </script>
</body>
</html>