<?php
session_start();
include 'koneksi.php';

if (!isset($_GET['id'])) {
    echo "ID tiket tidak ditemukan!";
    exit;
}

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM tiket WHERE id = '$id'");

if (mysqli_num_rows($result) == 0) {
    echo "Tiket tidak ditemukan!";
    exit;
}

$row = mysqli_fetch_assoc($result);

// Tentukan dashboard sesuai role
$dashboard_url = 'dashboard_user.php';
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $dashboard_url = 'dashboard_admin.php';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>e-Ticket Helpdesk #<?= $row['id']; ?></title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            padding: 40px;
        }

        .ticket-container {
            width: 800px;
            background: #fff;
            border: 2px solid #007bff;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            overflow: hidden;
            -webkit-print-color-adjust: exact;
        }

        .ticket-header {
            background: #007bff;
            color: white;
            padding: 15px 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .ticket-header h2 {
            margin: 0;
            font-size: 22px;
        }

        .ticket-header img {
            max-height: 50px;
            width: auto;
            object-fit: contain;
        }

        .ticket-body {
            display: flex;
            justify-content: space-between;
            padding: 20px 25px;
            border-bottom: 2px dashed #ccc;
        }

        .ticket-section {
            width: 48%;
        }

        .ticket-section p {
            margin: 8px 0;
            font-size: 15px;
        }

        .ticket-section strong {
            display: inline-block;
            width: 110px;
        }

        .ticket-footer {
            padding: 20px 25px;
            text-align: center;
        }

        .barcode {
            width: 100%;
            height: 40px;
            background: repeating-linear-gradient(
                90deg,
                #000,
                #000 2px,
                #fff 2px,
                #fff 4px
            );
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .btn-print, .btn-back {
            background: #007bff;
            color: white;
            padding: 10px 25px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin: 5px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-print:hover, .btn-back:hover {
            background: #0056b3;
        }

        @media print {
            body {
                background: white !important;
                margin: 0;
                padding: 0;
            }
            .ticket-container {
                width: 100%;
                border: 2px solid #007bff;
                box-shadow: none;
                border-radius: 0;
            }
            .btn-print, .btn-back {
                display: none !important;
            }
        }
    </style>
</head>
<body>

<div class="ticket-container">
    <div class="ticket-header">
        <h2>e-Ticket Helpdesk</h2>
        <img src="logo-pt.png" alt="PT. Samudera Mulia Abadi">
    </div>

    <div class="ticket-body">
        <div class="ticket-section">
            <p><strong>ID Tiket:</strong> <?= $row['id']; ?></p>
            <p><strong>Username:</strong> <?= $row['username']; ?></p>
            <p><strong>Client:</strong> <?= $row['client']; ?></p>
        </div>
        <div class="ticket-section">
            <p><strong>Topik:</strong> <?= $row['topik']; ?></p>
            <p><strong>Ringkasan:</strong><br><?= nl2br($row['ringkasan']); ?></p>
        </div>
    </div>

    <div class="ticket-footer">
        <div class="barcode"></div>
        <a href="<?= $dashboard_url ?>" class="btn-back">🔙 Kembali ke Dashboard</a>
        <button class="btn-print" onclick="window.print()">🖨️ Cetak / Simpan PDF</button>
    </div>
</div>

</body>
</html>
