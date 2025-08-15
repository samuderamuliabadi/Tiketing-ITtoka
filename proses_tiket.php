<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
$client = 'ITtoka';

// Tangkap data dari form
$topik = isset($_POST['topik']) ? $_POST['topik'] : '';
$topik_baru = isset($_POST['topik_baru']) ? trim($_POST['topik_baru']) : '';
$ringkasan = isset($_POST['ringkasan']) ? trim($_POST['ringkasan']) : '';

// Validasi sederhana
if (empty($ringkasan)) {
    echo "Ringkasan masalah tidak boleh kosong.";
    exit;
}

// Jika user mengisi topik baru, maka gunakan itu
if (!empty($topik_baru)) {
    $topik = $topik_baru;
}

// Simpan ke database
$query = "INSERT INTO tiket (username, client, topik, ringkasan, status) 
          VALUES (?, ?, ?, ?, 'open')";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ssss", $username, $client, $topik, $ringkasan);

if (mysqli_stmt_execute($stmt)) {
    header("Location: dashboard_user.php?berhasil=1");
    exit;
} else {
    echo "Gagal menyimpan tiket: " . mysqli_error($conn);
}
?>
