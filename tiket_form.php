<?php
session_start();
include 'koneksi.php'; // koneksi ke database

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['submit'])) {
    $username = $_SESSION['username']; // Ambil username admin yg login
    $client = 'ITtoka'; // Client default
    $topik = $_POST['topik'];
    $ringkasan = $_POST['ringkasan'];

    $query = "INSERT INTO tiket (username, client, topik, ringkasan) 
              VALUES ('$username', '$client', '$topik', '$ringkasan')";

    if (mysqli_query($conn, $query)) {
        echo "Tiket berhasil dibuat.";
        // redirect ke dashboard atau halaman lain
        header("Location: dashboard_admin.php");
        exit;
    } else {
        echo "Gagal membuat tiket: " . mysqli_error($conn);
    }
}
?>
