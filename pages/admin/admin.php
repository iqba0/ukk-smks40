<?php
session_start();
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;

include_once '../../db/db_config.php';

$error_message = "Username sudah tersedia"; // Deklarasi pesan error

// Tambah DATA atau Create
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tambah'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Hash password menggunakan MD5
    $nama = $_POST['nama'];
    $role = $_POST['role'];
    
    // Periksa apakah username sudah ada
    $query_check_username = "SELECT * FROM users WHERE username='$username'";
    $result_check_username = mysqli_query($conn, $query_check_username);
    if (mysqli_num_rows($result_check_username) > 0) {
        $error_message = "Error: Username '$username' sudah ada!";
    } else {
        $query_insert = "INSERT INTO users (username, password, nama, role) VALUES ('$username', '$password', '$nama', '$role')";
        $result_insert = mysqli_query($conn, $query_insert);
        if ($result_insert) {
            header("Location: $_SERVER[PHP_SELF]");
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}

// Edit atau Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit'])) {
    $id = $_POST['ID'];
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Hash password menggunakan MD5
    $nama = $_POST['nama'];
    $role = $_POST['role'];
    
    $query = "UPDATE users SET username='$username', password='$password', nama='$nama', role='$role' WHERE ID='$id'";
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
    
    $query = "DELETE FROM users WHERE ID='$id'";
    $result = mysqli_query($conn, $query);
    if ($result) {
        header("Location: $_SERVER[PHP_SELF]");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Ambil semua data atau READ data
$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);
$rows = [];
while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
}

// Data untuk mode edit
$edit_username = '';
$edit_password = '';
$edit_nama = '';
$edit_role = '';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM users WHERE ID='$id'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $edit_username = $row['username'];
    $edit_password = $row['password'];
    $edit_nama = $row['nama'];
    $edit_role = $row['role'];
}
?>
