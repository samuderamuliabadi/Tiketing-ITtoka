<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];
$tiket_id = $_GET['id'] ?? 0;

// Proses kirim balasan jika user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $role === 'user') {
    $pesan = mysqli_real_escape_string($conn, $_POST['pesan']);
    $query = "INSERT INTO balasan (tiket_id, username, pesan) VALUES ('$tiket_id', '$username', '$pesan')";
    mysqli_query($conn, $query);
    header("Location: reply_tiket.php?id=$tiket_id");
    exit;
}

// Ambil semua balasan tiket
$balasan = mysqli_query($conn, "SELECT * FROM balasan WHERE tiket_id='$tiket_id' ORDER BY created_at ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Balasan Tiket</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f7f7f7;
            padding: 30px;
        }
        .container {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 25px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
        }
        .chat-box {
            border: 1px solid #ccc;
            padding: 15px;
            max-height: 400px;
            overflow-y: auto;
            margin-bottom: 20px;
        }
        .chat-message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 8px;
        }
        .from-user {
            background: #d1e7dd;
            text-align: right;
        }
        .from-admin {
            background: #f8d7da;
            text-align: left;
        }
        .chat-message small {
            display: block;
            font-size: 0.8em;
            color: #555;
        }
        form textarea {
            width: 100%;
            height: 80px;
            resize: none;
            padding: 10px;
            margin-bottom: 10px;
        }
        button {
            display: block;
            padding: 10px 20px;
            margin: auto;
            background-color: #4CAF50;
            border: none;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .back-link {
            text-align: center;
            margin-top: 15px;
        }
        .back-link a {
            text-decoration: none;
            color: #333;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Balasan Tiket ID: <?= htmlspecialchars($tiket_id) ?></h2>

    <div class="chat-box">
        <?php if (mysqli_num_rows($balasan) === 0): ?>
            <p><i>Belum ada balasan.</i></p>
        <?php else: ?>
            <?php while ($row = mysqli_fetch_assoc($balasan)) {
                $is_user = $row['username'] !== 'admin'; ?>
                <div class="chat-message <?= $is_user ? 'from-user' : 'from-admin' ?>">
                    <?= nl2br(htmlspecialchars($row['pesan'])) ?>
                    <small><?= htmlspecialchars($row['username']) ?> • <?= $row['created_at'] ?></small>
                </div>
            <?php } ?>
        <?php endif; ?>
    </div>

    <?php if ($role === 'user'): ?>
    <form method="POST">
        <textarea name="pesan" placeholder="Tulis balasan kamu..." required></textarea>
        <button type="submit">Kirim Balasan</button>
    </form>
    <?php endif; ?>

    <div class="back-link">
        <a href="<?= $role === 'admin' ? 'dashboard_admin.php' : 'dashboard_user.php' ?>">← Kembali ke Dashboard</a>
    </div>
</div>
</body>
</html>
