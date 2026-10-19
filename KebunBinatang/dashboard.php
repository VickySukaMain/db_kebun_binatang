<?php
include "koneksi.php";
$judul = "Dashboard";
include "header.php";
function hitung($conn, $t) { return mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM $t"))[0]; }
?>
<div class="stats">
    <div class="stat"><span>🐘</span><b><?= hitung($conn, 'hewan') ?></b>Hewan</div>
    <div class="stat"><span>🏠</span><b><?= hitung($conn, 'kandang') ?></b>Kandang</div>
    <div class="stat"><span>🍖</span><b><?= hitung($conn, 'pakan') ?></b>Jadwal Pakan</div>
    <div class="stat"><span>👷</span><b><?= hitung($conn, 'petugas') ?></b>Petugas</div>
</div>
<h2>Hewan Terbaru</h2>
<div class="grid">
<?php
$r = mysqli_query($conn, "SELECT * FROM hewan ORDER BY id_hewan DESC LIMIT 4");
while ($h = mysqli_fetch_assoc($r)) { ?>
    <div class="animal">
        <?php if ($h['gambar']) echo "<img src='uploads/" . e($h['gambar']) . "'>"; else echo "<div class='noimg'>🐾</div>"; ?>
        <div class="body"><h3><?= e($h['nama_hewan']) ?></h3><p><?= e($h['spesies']) ?></p>
        <a class="btn sm" href="hewan_detail.php?id=<?= $h['id_hewan'] ?>">Lihat Detail</a></div>
    </div>
<?php } ?>
</div>
<?php include "footer.php"; ?>
