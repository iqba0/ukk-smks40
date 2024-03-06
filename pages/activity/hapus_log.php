<?php
// Lakukan pengecekan apakah metode yang digunakan adalah POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil ID log dari formulir
    $log_id = isset($_POST['log_id']) ? $_POST['log_id'] : null;

    // Lakukan validasi ID log
    if ($log_id) {
        // Koneksi ke database
        include '../../db/db_config.php';

        // Query untuk menghapus log berdasarkan ID
        $query = "DELETE FROM transaksi WHERE id = $log_id";

       // Eksekusi query penghapusan
        if (mysqli_query($conn, $query)) {
            echo "Log berhasil dihapus.";
            // Arahkan kembali ke halaman log activity setelah berhasil menghapus
            header("Location: log_activity.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }

        // Tutup koneksi database
        mysqli_close($conn);

    } else {
        echo "ID log tidak valid.";
    }
} else {
    // Jika metode yang digunakan bukan POST, tampilkan pesan kesalahan
    echo "Metode yang digunakan tidak valid.";
}
?>
