<?php
session_start();
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;

include_once '../../db/db_config.php';

// Tambah DATA atau Create
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tambah'])) {
    $nama_produk = $_POST['nama_produk'];
    // Menghilangkan tanda titik dari input harga sebelum menyimpannya
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

$sort = isset($_GET['sort']) ? $_GET['sort'] : 'nama_produk'; // Define $sort variable here
$order = isset($_GET['order']) ? $_GET['order'] : 'asc';

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
    <link rel="stylesheet" href="../../assets/style/product.css">
</head>
<body>
    <?php include '../sidebar.php' ?>
    <div class="container-fluid">
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
                    <!-- Bagian atas tabel -->
                    <div class="mb-3">
                        <label for="search" class="form-label">Cari:</label>
                        <input type="text" id="search" class="form-control">
                    </div>
                    <!-- Akhir bagian atas tabel -->
                    <table class="table table-striped mt-3">
                        <thead>
                            <tr>
                        <th scope="col">
                            <a href="#" onclick="sortTable(0)">Nama Produk <?php echo ($sort === 'nama_produk') ? ($order === 'asc' ? '<i class="bi bi-caret-up-fill"></i>' : '<i class="bi bi-caret-down-fill"></i>') : ''; ?></a>
                        </th>
                        <th scope="col">
                            <a href="#" onclick="sortTable(1)">Harga Produk <?php echo ($sort === 'harga_produk') ? ($order === 'asc' ? '<i class="bi bi-caret-up-fill"></i>' : '<i class="bi bi-caret-down-fill"></i>') : ''; ?></a>
                        </th>
                        <th scope="col">
                            <a href="#" onclick="sortTable(2)">Jumlah <?php echo ($sort === 'jumlah') ? ($order === 'asc' ? '<i class="bi bi-caret-up-fill"></i>' : '<i class="bi bi-caret-down-fill"></i>') : ''; ?></a>
                        </th>
                        <th scope="col">
                            <a href="#" onclick="sortTable(3)">Kode Unik <?php echo ($sort === 'kode_unik') ? ($order === 'asc' ? '<i class="bi bi-caret-up-fill"></i>' : '<i class="bi bi-caret-down-fill"></i>') : ''; ?></a>
                        </th>
                            </tr>
                        </thead>

                    <tbody>
                        <?php
                        // Tambahkan kode ini untuk memfilter data berdasarkan pencarian
                        $search = isset($_GET['search']) ? $_GET['search'] : '';
                        $rows_filtered = array_filter($rows, function($row) use ($search) {
                            return stripos($row['nama_produk'], $search) !== false ||
                                stripos($row['kode_unik'], $search) !== false;
                        });

                        // Tambahkan kode ini untuk mengurutkan data berdasarkan kolom yang dipilih
                        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'nama_produk';
                        $order = isset($_GET['order']) ? $_GET['order'] : 'asc';
                        usort($rows_filtered, function($a, $b) use ($sort, $order) {
                            return $order === 'asc' ? $a[$sort] <=> $b[$sort] : $b[$sort] <=> $a[$sort];
                        });

                        foreach ($rows_filtered as $row) :
                        ?>
                        <tr>
                            <td><?php echo $row['nama_produk']; ?></td>
                            <!-- Tampilkan harga dengan pemisah ribuan -->
                            <td>Rp <?php echo number_format($row['harga_produk'], 0, ',', '.'); ?></td>
                            <td><?php echo $row['jumlah']; ?></td>
                            <td><?php echo $row['kode_unik']; ?></td>
                            <td>
                                <!-- Bagian aksi -->
                                <?php
                                $self = $_SERVER['PHP_SELF'];
                                $id = $row['id'];
                                $edit_link = "$self?id=$id";
                                $delete_link = "$self?hapus=$id";
                                ?>
                                <a href="<?php echo $edit_link; ?>" class="btn btn-sm btn-primary">Edit</a>
                                <a href="<?php echo $delete_link; ?>" class="btn btn-sm btn-danger">Hapus</a>
                                <!-- Akhir bagian aksi -->
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
// Mendapatkan input pencarian
var inputSearch = document.getElementById('search');

// Menambahkan event listener untuk input pencarian
inputSearch.addEventListener('input', function(event) {
    // Mendapatkan nilai yang dimasukkan pengguna
    var searchValue = event.target.value.trim().toLowerCase();

    // Mengubah URL dengan menambahkan parameter pencarian
    var url = new URL(window.location.href);
    url.searchParams.set('search', searchValue);
    window.history.replaceState(null, null, url);

    // Memanggil fungsi untuk melakukan filter data
    filterData(searchValue);
});

// Memanggil fungsi untuk melakukan filter data saat halaman dimuat
filterData('');

// Fungsi untuk melakukan filter data
function filterData(searchValue) {
    // Mendapatkan semua baris data
    var rows = document.querySelectorAll('.table tbody tr');

    // Iterasi melalui setiap baris data
    rows.forEach(function(row) {
        // Mendapatkan nilai nama produk dan kode unik
        var namaProduk = row.cells[0].textContent.toLowerCase();
        var kodeUnik = row.cells[3].textContent.toLowerCase();

        // Menyembunyikan baris jika tidak sesuai dengan kriteria pencarian
        if (namaProduk.includes(searchValue) || kodeUnik.includes(searchValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Fungsi untuk mengatur pengurutan tabel
function sortTable(column) {
    var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
    table = document.querySelector('.table');
    switching = true;
    // Atur arah pengurutan
    dir = "asc"; 
    while (switching) {
        switching = false;
        rows = table.rows;
        for (i = 1; i < (rows.length - 1); i++) {
            shouldSwitch = false;
            x = rows[i].getElementsByTagName("TD")[column];
            y = rows[i + 1].getElementsByTagName("TD")[column];
            if (dir == "asc") {
                if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                    shouldSwitch = true;
                    break;
                }
            } else if (dir == "desc") {
                if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                    shouldSwitch = true;
                    break;
                }
            }
        }
        if (shouldSwitch) {
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
            switchcount ++;      
        } else {
            if (switchcount == 0 && dir == "asc") {
                dir = "desc";
                switching = true;
            }
        }
    }
    event.preventDefault(); // Mencegah perilaku default dari tautan
}
</script>
</body>
</html>
