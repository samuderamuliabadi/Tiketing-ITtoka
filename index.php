<?php
session_start();
include 'koneksi.php';

$error = "";

// Auto login jika ada cookie
if (!isset($_SESSION['username']) && isset($_COOKIE['remember_username']) && isset($_COOKIE['remember_token'])) {
    $username = $_COOKIE['remember_username'];
    $token = $_COOKIE['remember_token'];

    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND remember_token='$token'");
    if ($row = mysqli_fetch_assoc($query)) {
        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $row['role'];

        if ($row['role'] === 'admin') {
            header("Location: dashboard_admin.php");
        } else {
            header("Location: dashboard_user.php");
        }
        exit;
    }
}

// Proses login manual
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $remember = isset($_POST['remember']);

    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    if ($row = mysqli_fetch_assoc($query)) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];

            if ($remember) {
                $token = bin2hex(random_bytes(16));
                setcookie('remember_username', $row['username'], time() + (86400 * 30), "/");
                setcookie('remember_token', $token, time() + (86400 * 30), "/");
                mysqli_query($conn, "UPDATE users SET remember_token='$token' WHERE username='$username'");
            }

            if ($row['role'] === 'admin') {
                header("Location: dashboard_admin.php");
            } else {
                header("Location: dashboard_user.php");
            }
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Helpdesk</title>
    <style>
    body {
    font-family: Arial, sans-serif;
    background: url('help.jpg') no-repeat center center fixed;
    background-size: cover;
    margin: 0;
    padding: 0;
    position: relative;
    height: 100vh;
}

/* Layer putih transparan full layar */
body::before {
    content: "";
    position: fixed; /* full layar & tetap saat scroll */
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.5); /* Putih transparan (atur 0.5 untuk opasitas) */
    z-index: 0;
}

.login-container {
    position: relative;
    z-index: 1; /* di atas layer putih */
    width: 400px;
    background: rgba(255, 255, 255, 0.95);
    padding: 30px;
    margin: 80px auto;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    backdrop-filter: blur(5px);
    -webkit-backdrop-filter: blur(5px);
}

.login-container img {
    display: block;
    margin: 0 auto 10px auto;
    width: 80px;
}

h1 {
    font-size: 22px;
    font-weight: bold;
    margin-bottom: 15px;
    text-align: center;
    color: #007bff;
}

input[type="text"], input[type="password"] {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}

.form-options {
    display: flex;
    justify-content: flex-start;
    align-items: center;
    margin: 5px 0 15px 0;
    font-size: 14px;
}

.form-options input {
    margin-right: 5px;
}

button {
    width: 100%;
    padding: 12px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
}

button:hover {
    background: #0056b3;
}

.error {
    color: red;
    text-align: center;
}

.register-link {
    text-align: center;
    margin-top: 10px;
}

.register-link a {
    color: #007bff;
    text-decoration: none;
}
    </style>
</head>
<body>
    <div class="login-container">
        <img src="logo-pt.png" alt="Logo">
        <h1>Tiketing Helpdesk - Toka SMA</h1>
        <?php if ($error): ?>
            <p class="error"><?= $error; ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>

            <div class="form-options">
                <label>
                    <input type="checkbox" name="remember"> Remember Me
                </label>
            </div>

            <button type="submit">Login</button>
        </form>
        <div class="register-link">
            Belum punya akun? <a href="register.php">Buat akun</a>
        </div>
    </div>
</body>
</html>
