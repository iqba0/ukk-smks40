<?php require_once ('kasir.php');?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Kasir</title>
    <link rel="shortcut icon" type="image/x-icon" href="../../assets/img/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/style/sidebar.css">
    <link rel="stylesheet" href="../../assets/style/kasir.css">
</head>
<body>
<div class="container-fluid">
<?php include '../sidebar.php';?>
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
    <script>
        function printStruk() {
            window.print();
        }
        document.getElementById('uang').addEventListener('input', function() {
            var uang = parseFloat(this.value);
            var totalHarga = <?php echo $totalHarga; ?>;
            var kembalian = uang - totalHarga;
            document.getElementById('kembalian').value = kembalian >= 0 ? kembalian : 0;
        });
    </script>
</body>
</html>
