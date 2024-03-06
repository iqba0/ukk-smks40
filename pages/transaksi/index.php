<?php
session_start();
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;

if (!$role) {
    header("Location: ../");
    exit();
}
include_once '../../db/db_config.php';

// Fungsi untuk mencari produk berdasarkan kata kunci
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

// Fungsi untuk menambahkan produk ke dalam transaksi
function tambahkanProduk($id, $jumlah) {
    global $conn;
    $query = "SELECT * FROM products WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $produk = mysqli_fetch_assoc($result);
    if ($jumlah <= 0) {
        return false;
    } elseif ($jumlah > $produk['jumlah']) {
        return false; 
    } else {
        $produk['jumlah'] = $jumlah;
        return $produk;
    }
}

// Fungsi untuk mengurangi jumlah produk dalam transaksi
function kurangiProduk($index) {
    $struk = isset($_SESSION['struk']) ? $_SESSION['struk'] : [];
    unset($struk[$index]);
    $_SESSION['struk'] = array_values($struk);
}

// Fungsi untuk mengecek apakah produk sudah ada dalam transaksi
function cekProduk($produk, $struk) {
    foreach ($struk as $index => $item) {
        if ($item['id'] == $produk['id']) {
            return $index;
        }
    }
    return -1;
}

// Mendapatkan data transaksi dari session
$struk = isset($_SESSION['struk']) ? $_SESSION['struk'] : [];
$totalHarga = isset($_SESSION['totalHarga']) ? $_SESSION['totalHarga'] : 0;
$error = '';

// Mendapatkan data produk berdasarkan pencarian atau menampilkan semua produk
$rows = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['keyword'])) {
    $keyword = $_POST['keyword'];
    $rows = cariProduk($keyword);
} else {
    $query = "SELECT * FROM products";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
}

// Menambahkan produk ke dalam transaksi jika permintaan POST diterima
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id']) && isset($_GET['tambah'])) {
    $id_produk = $_GET['id'];
    $jumlah = isset($_GET['jumlah']) ? $_GET['jumlah'] : 1;
    $produk = tambahkanProduk($id_produk, $jumlah);
    if ($produk === false) {
        $error = 'Jumlah produk tidak valid!';
    } else {
        $index = cekProduk($produk, $struk);
        if ($index != -1) {
            $struk[$index]['jumlah'] += $jumlah;
        } else {
            array_push($struk, $produk);
        }
        // Menghitung total harga setelah menambahkan produk
        $totalHarga = 0;
        foreach ($struk as $item) {
            $totalHarga += $item['harga_produk'] * $item['jumlah'];
        }
        $_SESSION['struk'] = $struk;
        $_SESSION['totalHarga'] = $totalHarga;
    }
}

// Melakukan tindakan ketika tombol "Kurang" diklik untuk mengurangi jumlah produk dalam transaksi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['kurang'])) {
    $index = $_POST['index'];
    kurangiProduk($index);
    header("Location: " . $_SERVER['PHP_SELF']); 
}

// Melakukan tindakan ketika tombol "Cetak" diklik untuk menyimpan transaksi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cetak'])) {
    if (isset($_POST['uang']) && isset($_POST['kembalian'])) {
        $uang = $_POST['uang'];
        $kembalian_transaksi = $_POST['kembalian'];

        // Memasukkan informasi transaksi ke dalam tabel 'transaksi'
        $query_transaksi = "INSERT INTO transaksi (uang_pelanggan, kembalian, total_harga) VALUES ($uang, $kembalian_transaksi, $totalHarga)";
        $result_transaksi = mysqli_query($conn, $query_transaksi);
        if (!$result_transaksi) {
            $error = 'Gagal menyimpan transaksi!';
        } else {
            // Mendapatkan ID transaksi yang baru saja dimasukkan
            $id_transaksi = mysqli_insert_id($conn);

            // Memasukkan informasi produk dalam transaksi ke dalam tabel 'transaksi_produk' dan mengurangi jumlah produk dalam tabel 'products'
            foreach ($struk as $produk) {
                $nama_produk = $produk['nama_produk'];
                $harga_produk = $produk['harga_produk'];
                $jumlah = $produk['jumlah'];
                $kode_unik = $produk['kode_unik'];
                $totalHargaProduk = $harga_produk * $jumlah;
                
                $query_produk = "INSERT INTO transaksi_produk (id_transaksi, nama_produk, harga_produk, jumlah, kode_unik, total_harga) VALUES ($id_transaksi, '$nama_produk', '$harga_produk', $jumlah, '$kode_unik', $totalHargaProduk)";
                $result_produk = mysqli_query($conn, $query_produk);
                if (!$result_produk) {
                    $error = 'Gagal menyimpan informasi produk dalam transaksi!';
                    break; 
                }

                $query_update_produk = "UPDATE products SET jumlah = jumlah - $jumlah WHERE nama_produk = '$nama_produk'";
                $result_update_produk = mysqli_query($conn, $query_update_produk);
                if (!$result_update_produk) {
                    $error = 'Gagal mengurangi jumlah produk!';
                    break; 
                }
            }

            // Mengosongkan session transaksi dan total harga setelah transaksi selesai
            $_SESSION['struk'] = [];
            $_SESSION['totalHarga'] = 0;
            $struk = [];
            $totalHarga = 0;

            // Mengarahkan ke halaman cetak struk dengan ID transaksi
            header("Location: cetak_struk.php?id_transaksi=$id_transaksi");
            exit(); 
        }
    } else {
        $error = "Mohon lengkapi informasi uang dan kembalian.";
    }
}

// Melakukan tindakan ketika tombol "Perbarui Transaksi" diklik untuk mengosongkan transaksi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['refresh'])) {
    $_SESSION['struk'] = [];
    $_SESSION['totalHarga'] = 0;
    $struk = [];
    $totalHarga = 0;
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
    <link rel="shortcut icon" type="image/x-icon" href="../../assets/img/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../style/style.css">
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
