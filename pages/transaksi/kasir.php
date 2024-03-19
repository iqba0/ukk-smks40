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