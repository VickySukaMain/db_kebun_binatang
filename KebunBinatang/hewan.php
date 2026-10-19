<?php
include "koneksi.php";
$judul = "Data Hewan";
include "header.php";
$cari = $_GET['cari'] ?? '';
$like = "%$cari%";
$r = ambil($conn, "SELECT h.*, k.nomor_kandang FROM hewan h LEFT JOIN kandang k ON h.id_kandang=k.id_kandang
    WHERE h.nama_hewan LIKE ? OR h.spesies LIKE ? OR h.jenis LIKE ? ORDER BY h.id_hewan DESC", "sss", $like, $like, $like);
?>
<?php if (isset($_GET['ok'])) echo '<div class="alert ok">Berhasil disimpan.</div>'; ?>
<div class="toolbar">
    <form method="get"><input type="text" name="cari" placeholder="Cari nama / jenis / spesies..." value="<?= e($cari) ?>"> <button class="btn sm">Cari</button></form>
    <?php if (is_admin()) echo '<a class="btn" href="hewan_form.php">+ Tambah Hewan</a>'; ?>
</div>
<div class="grid">
<?php while ($h = mysqli_fetch_assoc($r)) { ?>
    <div class="animal">
        <?php if ($h['gambar']) echo "<img src='uploads/" . e($h['gambar']) . "'>"; else echo "<div class='noimg'>🐾</div>"; ?>
        <div class="body">
            <h3><?= e($h['nama_hewan']) ?></h3>
            <p><?= e($h['jenis']) ?> • <?= e($h['spesies']) ?></p>
            <p>Kandang: <?= e($h['nomor_kandang'] ?? '-') ?></p>
            <a class="btn sm" href="hewan_detail.php?id=<?= $h['id_hewan'] ?>">Detail</a>
            <?php if (is_admin()) { ?>
                <a class="btn sm kuning" href="hewan_form.php?id=<?= $h['id_hewan'] ?>">Edit</a>
                <a class="btn sm merah" href="hewan_hapus.php?id=<?= $h['id_hewan'] ?>" onclick="return confirm('Hapus hewan ini beserta data pakan & perawatannya?')">Hapus</a>
            <?php } ?>
        </div>
    </div>
<?php } ?>
</div>
<?php include "footer.php"; ?>
