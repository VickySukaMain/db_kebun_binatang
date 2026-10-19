<?php
include "koneksi.php";
$u = $_POST['username'] ?? '';
$p = $_POST['password'] ?? '';
$r = ambil($conn, "SELECT * FROM users WHERE username=?", "s", $u);
$d = mysqli_fetch_assoc($r);
if ($d && password_verify($p, $d['password'])) {
    $_SESSION['user'] = $d['nama_lengkap'];
    $_SESSION['role'] = $d['role'];
    header("Location: dashboard.php");
} else {
    header("Location: index.php?gagal=1");
}
