<?php
session_start();
if ($_SESSION['role'] != 'owner') {
    header("Location: index.php");
    exit();
}
?>
<!-- Tampilan Laporan -->
