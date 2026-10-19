<?php
// Jalankan SEKALI saja: http://localhost/KebunBinatang/setup.php
include "koneksi.php";
$log = [];
function cek($conn, $sql, $ket) {
    global $log;
    $ok = mysqli_query($conn, $sql);
    $log[] = ($ok ? "✅ " : "⚠️ ") . $ket . ($ok ? "" : " → " . mysqli_error($conn));
}
cek($conn, "CREATE TABLE IF NOT EXISTS users (
    id_user INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    role ENUM('admin','petugas') NOT NULL DEFAULT 'petugas'
)", "Tabel users");

$r = mysqli_query($conn, "SHOW COLUMNS FROM hewan LIKE 'gambar'");
if (mysqli_num_rows($r) == 0) cek($conn, "ALTER TABLE hewan ADD gambar VARCHAR(255) NULL", "Kolom gambar di tabel hewan");
else $log[] = "✅ Kolom gambar sudah ada";

cek($conn, "ALTER TABLE hewan MODIFY id_hewan INT NOT NULL AUTO_INCREMENT", "AUTO_INCREMENT hewan");
cek($conn, "ALTER TABLE kandang MODIFY id_kandang INT NOT NULL AUTO_INCREMENT", "AUTO_INCREMENT kandang");
cek($conn, "ALTER TABLE pakan MODIFY id_pakan INT NOT NULL AUTO_INCREMENT", "AUTO_INCREMENT pakan");
cek($conn, "ALTER TABLE perawatan MODIFY id_perawatan INT NOT NULL AUTO_INCREMENT", "AUTO_INCREMENT perawatan");
cek($conn, "ALTER TABLE petugas MODIFY id_petugas INT NOT NULL AUTO_INCREMENT", "AUTO_INCREMENT petugas");

$n = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM users"))[0];
if ($n == 0) {
    jalankan($conn, "INSERT INTO users (username,password,nama_lengkap,role) VALUES (?,?,?,?)", "ssss", "admin", password_hash("admin123", PASSWORD_DEFAULT), "Administrator", "admin");
    jalankan($conn, "INSERT INTO users (username,password,nama_lengkap,role) VALUES (?,?,?,?)", "ssss", "petugas", password_hash("petugas123", PASSWORD_DEFAULT), "Petugas Kebun", "petugas");
    $log[] = "✅ Akun dibuat: admin / admin123 dan petugas / petugas123";
}
if (!is_dir("uploads")) { mkdir("uploads"); $log[] = "✅ Folder uploads dibuat"; }
?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Setup</title><link rel="stylesheet" href="style.css"></head>
<body><div class="container"><div class="card"><h2>Setup Database</h2>
<?php foreach ($log as $l) echo "<p>" . e($l) . "</p>"; ?>
<p><b>Selesai.</b> <a href="index.php">Ke halaman login</a> (hapus file setup.php setelah ini)</p>
</div></div></body></html>
