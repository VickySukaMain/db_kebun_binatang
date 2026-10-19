<?php
include "koneksi.php";
wajib_login();
$pesan = $_GET['pesan'] ?? '';
if (isset($_GET['hapus'])) {
    wajib_admin();
    jalankan($conn, "DELETE FROM perawatan WHERE id_perawatan=?", "i", (int)$_GET['hapus']);
    header("Location: perawatan.php?pesan=Data+dihapus."); exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    wajib_admin();
    $id = (int)$_POST['id']; $hw = (int)$_POST['id_hewan']; $pt = (int)$_POST['id_petugas'];
    $pk = $_POST['pemeriksaan_kesehatan']; $pr = $_POST['perawatan']; $tg = $_POST['tanggal'];
    if ($id) jalankan($conn, "UPDATE perawatan SET id_hewan=?,id_petugas=?,pemeriksaan_kesehatan=?,perawatan=?,tanggal=? WHERE id_perawatan=?", "iisssi", $hw, $pt, $pk, $pr, $tg, $id);
    else jalankan($conn, "INSERT INTO perawatan (id_hewan,id_petugas,pemeriksaan_kesehatan,perawatan,tanggal) VALUES (?,?,?,?,?)", "iisss", $hw, $pt, $pk, $pr, $tg);
    header("Location: perawatan.php?pesan=Data+tersimpan."); exit;
}
$e = ['id_perawatan'=>0,'id_hewan'=>'','id_petugas'=>'','pemeriksaan_kesehatan'=>'','perawatan'=>'','tanggal'=>date('Y-m-d')];
if (isset($_GET['edit'])) $e = mysqli_fetch_assoc(ambil($conn, "SELECT * FROM perawatan WHERE id_perawatan=?", "i", (int)$_GET['edit'])) ?: $e;
$judul = "Data Perawatan";
include "header.php";
if ($pesan) echo "<div class='alert ok'>" . e($pesan) . "</div>";
if (is_admin()) { ?>
<form class="card form inline" method="post">
    <input type="hidden" name="id" value="<?= $e['id_perawatan'] ?>">
    <select name="id_hewan" required><option value="">-- hewan --</option>
    <?php $hw = mysqli_query($conn, "SELECT id_hewan,nama_hewan FROM hewan ORDER BY nama_hewan");
    while ($h = mysqli_fetch_assoc($hw)) echo "<option value='{$h['id_hewan']}' " . ($e['id_hewan'] == $h['id_hewan'] ? 'selected' : '') . ">" . e($h['nama_hewan']) . "</option>"; ?>
    </select>
    <select name="id_petugas" required><option value="">-- petugas --</option>
    <?php $pt = mysqli_query($conn, "SELECT id_petugas,nama_petugas FROM petugas ORDER BY nama_petugas");
    while ($p = mysqli_fetch_assoc($pt)) echo "<option value='{$p['id_petugas']}' " . ($e['id_petugas'] == $p['id_petugas'] ? 'selected' : '') . ">" . e($p['nama_petugas']) . "</option>"; ?>
    </select>
    <?= pilihan('pemeriksaan_kesehatan', ['Sehat','Pemeriksaan rutin','Demam','Luka','Cedera','Infeksi','Parasit','Tidak nafsu makan','Hamil'], $e['pemeriksaan_kesehatan'], '-- pemeriksaan --') ?>
    <?= pilihan('perawatan', ['Pemberian vitamin','Vaksinasi','Pemberian obat','Penanganan luka','Pemeriksaan gigi','Pembersihan kandang','Pemandian','Observasi'], $e['perawatan'], '-- perawatan --') ?>
    <input type="date" name="tanggal" value="<?= e($e['tanggal']) ?>" required>
    <button class="btn"><?= $e['id_perawatan'] ? 'Update' : 'Tambah' ?></button>
    <?php if ($e['id_perawatan']) echo '<a class="btn abu" href="perawatan.php">Batal</a>'; ?>
</form>
<?php } ?>
<div class="card"><table>
<tr><th>Tanggal</th><th>Hewan</th><th>Petugas</th><th>Pemeriksaan</th><th>Perawatan</th><?= is_admin() ? '<th>Aksi</th>' : '' ?></tr>
<?php $r = mysqli_query($conn, "SELECT r.*, h.nama_hewan, p.nama_petugas FROM perawatan r LEFT JOIN hewan h ON r.id_hewan=h.id_hewan LEFT JOIN petugas p ON r.id_petugas=p.id_petugas ORDER BY r.tanggal DESC");
while ($x = mysqli_fetch_assoc($r)) { ?>
<tr><td><?= e($x['tanggal']) ?></td><td><?= e($x['nama_hewan']) ?></td><td><?= e($x['nama_petugas']) ?></td><td><?= e($x['pemeriksaan_kesehatan']) ?></td><td><?= e($x['perawatan']) ?></td>
<?php if (is_admin()) { ?><td><a class="btn sm kuning" href="?edit=<?= $x['id_perawatan'] ?>">Edit</a> <a class="btn sm merah" href="?hapus=<?= $x['id_perawatan'] ?>" onclick="return confirm('Hapus data ini?')">Hapus</a></td><?php } ?></tr>
<?php } ?>
</table></div>
<?php include "footer.php"; ?>
