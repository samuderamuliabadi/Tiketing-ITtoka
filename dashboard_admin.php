<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$username = $_SESSION['username'];
$tiket_query = mysqli_query($conn, "SELECT * FROM tiket ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: url('pull.jpg') no-repeat center center fixed;
            background-size: cover;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: rgba(255, 255, 255, 0.9); /* transparan agar teks tetap terbaca */
            padding: 20px;
            border-radius: 12px;
        }
        h1 {
            text-align: center;
        }
        .form-box, .tiket-box {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 15px;
        }
        .form-group label {
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            width: 100%;
            box-sizing: border-box;
        }
        .form-group textarea {
            resize: vertical;
        }
        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            display: block;
            margin: 20px auto 0 auto;
        }
        button:hover {
            background-color: #0056b3;
        }
        .logout {
            text-align: center;
            margin-top: 20px;
        }
        .tiket-entry {
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }
        .tiket-entry:last-child {
            border-bottom: none;
        }
        .label-inline {
            font-weight: bold;
        }
    </style>
    <script>
        function toggleManualTopik() {
            const dropdown = document.getElementById('topik');
            const manualInput = document.getElementById('manualTopik');
            if (dropdown.value === 'lainnya') {
                manualInput.style.display = 'block';
            } else {
                manualInput.style.display = 'none';
            }
        }
    </script>
</head>
<body>

<div class="container">
    <h1>Admin - Selamat datang, <?= htmlspecialchars($username) ?>!</h1>

    <div class="form-box">
        <h2>Buat Tiket Baru</h2>
        <form action="proses_tiket.php" method="POST">

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" value="<?= htmlspecialchars($username) ?>" readonly>
            </div>

            <div class="form-group">
                <label>Client</label>
                <input type="text" name="client" value="ITtoka" readonly>
            </div>

            <div class="form-group">
                <label>Topik Permasalahan</label>
                <select name="topik" id="topik" onchange="toggleManualTopik()" required>
                    <option value="">--Pilih Topik--</option>
                    <option>Anti Virus Expired</option>
                    <option>Cek CCTV Attendance/Event</option>
                    <option>Change Email Password</option>
                    <option>Create Email</option>
                    <option>Change Password Email</option>
                    <option>Email Error</option>
                    <option>Jaringan Error</option>
                    <option>Create Face Recognition</option>
                    <option>Upgrade RAN</option>
                    <option>Setup Printer</option>
                    <option>Printer Error</option>
                    <option>Setting Scanner</option>
                    <option>Setting Email/Outlook</option>
                    <option>Register Face at ZKTeco</option>
                    <option>New Installation PC</option>
                    <option>New Installation Laptop</option>
                    <option>Perbaikan Wifi</option>
                    <option>Perbaikan LAN</option>
                    <option>PC/Laptop Error</option>
                    <option value="lainnya">+ Tambahkan Topik Baru</option>
                </select>
            </div>

            <div class="form-group" id="manualTopik" style="display: none;">
                <label>Topik Baru</label>
                <input type="text" name="topik_manual" placeholder="Masukkan Topik Baru">
            </div>

            <div class="form-group">
                <label>Ringkasan Masalah</label>
                <textarea name="ringkasan" rows="4" placeholder="Jelaskan permasalahan dengan jelas..." required></textarea>
            </div>

            <button type="submit">Kirim Tiket</button>
        </form>
    </div>

    <div class="tiket-box">
        <h2>Daftar Semua Tiket</h2>
        <?php while ($row = mysqli_fetch_assoc($tiket_query)) { ?>
            <div class="tiket-entry">
                <div><span class="label-inline">Username:</span> <?= htmlspecialchars($row['username']) ?></div>
                <div><span class="label-inline">Client:</span> <?= htmlspecialchars($row['client']) ?></div>
                <div><span class="label-inline">Topik:</span> <?= htmlspecialchars($row['topik']) ?></div>
                <div><span class="label-inline">Status:</span> <?= htmlspecialchars($row['status']) ?></div>
                <div style="margin-top: 10px;">
                    <a href="tiket_pdf.php?id=<?= $row['id'] ?>" target="_blank">📄 Lihat PDF</a> |
                    <a href="reply_tiket.php?id=<?= $row['id'] ?>">✉️ Balas Tiket</a>
                </div>
            </div>
        <?php } ?>
    </div>

    <div class="logout">
        <form action="logout.php" method="POST">
            <button type="submit">Logout</button>
        </form>
    </div>
</div>

</body>
</html>
