<?php
include "koneksi.php";
wajib_login();
$id = (int)($_GET['id'] ?? 0);
$h = mysqli_fetch_assoc(ambil($conn, "SELECT h.*, k.nomor_kandang, k.lokasi, k.kapasitas FROM hewan h LEFT JOIN kandang k ON h.id_kandang=k.id_kandang WHERE h.id_hewan=?", "i", $id));
if (!$h) { header("Location: hewan.php"); exit; }
$judul = "Detail: " . $h['nama_hewan'];
include "header.php";
$pakan = ambil($conn, "SELECT * FROM pakan WHERE id_hewan=? ORDER BY jadwal_pemberian", "i", $id);
$rawat = ambil($conn, "SELECT r.*, p.nama_petugas FROM perawatan r LEFT JOIN petugas p ON r.id_petugas=p.id_petugas WHERE r.id_hewan=? ORDER BY r.tanggal DESC", "i", $id);
?>
<div class="card detail">
    <div class="foto">
        <?php if ($h['gambar']) echo "<img src='uploads/" . e($h['gambar']) . "'>"; else echo "<div class='noimg big'>🐾</div>"; ?>
    </div>
    <div class="info">
        <h2><?= e($h['nama_hewan']) ?></h2>
        <table class="kv">
            <tr><td>Jenis</td><td><?= e($h['jenis']) ?></td></tr>
            <tr><td>Spesies</td><td><?= e($h['spesies']) ?></td></tr>
            <tr><td>Umur</td><td><?= e($h['umur']) ?> tahun</td></tr>
            <tr><td>Jenis Kelamin</td><td><?= e($h['jenis_kelamin']) ?></td></tr>
            <tr><td>Kandang</td><td><?= e($h['nomor_kandang'] ?? '-') ?> (<?= e($h['lokasi'] ?? '-') ?>), kapasitas <?= e($h['kapasitas'] ?? '-') ?></td></tr>
        </table>
    </div>
</div>
<div class="card"><h3>🍖 Jadwal Pakan</h3>
<table><tr><th>Jenis Pakan</th><th>Jam</th></tr>
<?php $ada = false; while ($p = mysqli_fetch_assoc($pakan)) { $ada = true; echo "<tr><td>" . e($p['jenis_pakan']) . "</td><td>" . e(substr($p['jadwal_pemberian'], 0, 5)) . "</td></tr>"; }
if (!$ada) echo "<tr><td colspan='2'>Belum ada jadwal pakan.</td></tr>"; ?>
</table></div>
<div class="card"><h3>💉 Riwayat Perawatan</h3>
<table><tr><th>Tanggal</th><th>Pemeriksaan</th><th>Perawatan</th><th>Petugas</th></tr>
<?php $ada = false; while ($x = mysqli_fetch_assoc($rawat)) { $ada = true;
    echo "<tr><td>" . e($x['tanggal']) . "</td><td>" . e($x['pemeriksaan_kesehatan']) . "</td><td>" . e($x['perawatan']) . "</td><td>" . e($x['nama_petugas'] ?? '-') . "</td></tr>"; }
if (!$ada) echo "<tr><td colspan='4'>Belum ada riwayat perawatan.</td></tr>"; ?>
</table></div>
<a class="btn abu" href="hewan.php">← Kembali</a>
<?php include "footer.php"; ?>
