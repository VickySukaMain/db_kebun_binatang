<?php
include "koneksi.php";
if (isset($_SESSION['user'])) { header("Location: dashboard.php"); exit; }
?>
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Login - Kebun Binatang</title><link rel="stylesheet" href="style.css"></head>
<body class="login-bg">
<form class="login-box" method="post" action="login_proses.php">
    <div class="logo">🦁</div>
    <h2>Sistem Informasi Kebun Binatang</h2>
    <?php if (isset($_GET['gagal'])) echo '<div class="alert err">Username atau password salah!</div>'; ?>
    <?php if (isset($_GET['logout'])) echo '<div class="alert ok">Berhasil keluar.</div>'; ?>
    <label>Username</label><input type="text" name="username" required autofocus>
    <label>Password</label><input type="password" name="password" required>
    <button class="btn" type="submit">Masuk</button>
</form>
</body></html>
