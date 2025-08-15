<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

// Ambil tiket dengan status 'open' saja
$query = mysqli_query($conn, "SELECT id, username, client, topik, ringkasan, status FROM tiket WHERE status = 'open'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard User</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #34495e;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h2 {
            margin: 0;
            font-size: 22px;
        }

        .logout-btn {
            background-color: #e74c3c;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.3s;
        }

        .logout-btn:hover {
            background-color: #c0392b;
        }

        .container {
            max-width: 1100px;
            margin: 40px auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        h3 {
            text-align: center;
            margin-bottom: 30px;
            color: #2c3e50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 14px 18px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #ecf0f1;
        }

        a {
            color: #2980b9;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        .empty {
            text-align: center;
            padding: 20px;
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>

<div class="navbar">
    <h2>Selamat Datang, <?= htmlspecialchars($username); ?></h2>
    <a href="logout.php" class="logout-btn">Logout</a>
</div>

<div class="container">
    <h3>Daftar Tiket Aktif</h3>

    <?php if (mysqli_num_rows($query) > 0): ?>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Client</th>
            <th>Topik</th>
            <th>Ringkasan</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($query)) { ?>
        <tr>
            <td><?= htmlspecialchars($row['id']); ?></td>
            <td><?= htmlspecialchars($row['username']); ?></td>
            <td><?= htmlspecialchars($row['client']); ?></td>
            <td><?= htmlspecialchars($row['topik']); ?></td>
            <td><?= htmlspecialchars($row['ringkasan']); ?></td>
            <td><?= htmlspecialchars($row['status']); ?></td>
            <td>
                <a href="reply_tiket.php?id=<?= $row['id']; ?>">Balas</a> |
                <a href="tiket_pdf.php?id=<?= $row['id']; ?>" target="_blank">Download PDF</a> |
                <a href="close_tiket.php?id=<?= $row['id']; ?>" onclick="return confirm('Tutup tiket ini secara permanen?')">Tutup</a>
            </td>
        </tr>
        <?php } ?>
    </table>
    <?php else: ?>
        <div class="empty">Tidak ada tiket aktif untuk saat ini.</div>
    <?php endif; ?>
</div>

</body>
</html>
