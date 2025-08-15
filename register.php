<?php
include 'koneksi.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
    $role     = $_POST['role'];

    $check = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    if (mysqli_num_rows($check) > 0) {
        $message = "<span style='color:red;'>❌ Username sudah digunakan!</span>";
    } else {
        $insert = mysqli_query($conn, "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')");
        if ($insert) {
            $message = "<span style='color:green;'>✅ Berhasil daftar. <a href='login.php'>Login di sini</a></span>";
        } else {
            $message = "<span style='color:red;'>❌ Gagal daftar!</span>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register Akun</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('helpdesk.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            position: relative;
            height: 100vh;
        }

        /* Layer putih transparan full layar */
        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.5);
            z-index: 0;
        }

        .login-container {
            position: relative;
            z-index: 1;
            width: 400px;
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            margin: 80px auto;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
        }

        h1 {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 15px;
            text-align: center;
            color: #007bff;
        }

        label {
            font-weight: bold;
            font-size: 14px;
        }

        input[type="text"], 
        input[type="password"], 
        select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper input[type="password"],
        .password-wrapper input[type="text"] {
            padding-right: 80px;
        }

        .password-wrapper label.show-label {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 13px;
            color: #007bff;
            cursor: pointer;
            user-select: none;
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

        .message {
            text-align: center;
            margin-top: 15px;
        }

        /* Link ke login */
        .login-link {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }
        .login-link a {
            color: #007bff;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h1>Form Register</h1>
    <form method="POST">
        <label>Username</label>
        <input type="text" name="username" required autocomplete="off">

        <label>Password</label>
        <div class="password-wrapper">
            <input type="password" id="password" name="password" required>
            <label class="show-label" onclick="togglePassword()">Tampilkan</label>
        </div>

        <label>Daftar Sebagai</label>
        <select name="role" required>
            <option value="">-- Pilih Peran --</option>
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>

        <button type="submit">Daftar</button>

        <!-- Tambahan link login -->
        <div class="login-link">
            Sudah punya akun? <a href="login.php">Login</a>
        </div>
    </form>

    <div class="message"><?= $message ?></div>
</div>

<script>
function togglePassword() {
    const pw = document.getElementById("password");
    pw.type = pw.type === "password" ? "text" : "password";
}
</script>

</body>
</html>
