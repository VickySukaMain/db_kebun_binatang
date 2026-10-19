<?php
include "koneksi.php";
wajib_admin();
$id = (int)($_GET['id'] ?? 0);
$d = mysqli_fetch_assoc(ambil($conn, "SELECT gambar FROM hewan WHERE id_hewan=?", "i", $id));
jalankan($conn, "DELETE FROM pakan WHERE id_hewan=?", "i", $id);
jalankan($conn, "DELETE FROM perawatan WHERE id_hewan=?", "i", $id);
if (jalankan($conn, "DELETE FROM hewan WHERE id_hewan=?", "i", $id) && !empty($d['gambar']) && file_exists("uploads/" . $d['gambar'])) {
    unlink("uploads/" . $d['gambar']);
}
header("Location: hewan.php?ok=1");
