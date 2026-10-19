<?php
include "koneksi.php";
wajib_login();
$pesan = $_GET['pesan'] ?? '';
if (isset($_GET['hapus'])) {
    wajib_admin();
    $ok = jalankan($conn, "DELETE FROM petugas WHERE id_petugas=?", "i", (int)$_GET['hapus']);
    header("Location: petugas.php?pesan=" . urlencode($ok ? "Data dihapus." : "Gagal hapus: petugas masih punya data perawatan.")); exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    wajib_admin();
    $id = (int)$_POST['id']; $nm = $_POST['nama_petugas']; $tg = $_POST['tugas'];
    if ($id) jalankan($conn, "UPDATE petugas SET nama_petugas=?,tugas=? WHERE id_petugas=?", "ssi", $nm, $tg, $id);
    else jalankan($conn, "INSERT INTO petugas (nama_petugas,tugas) VALUES (?,?)", "ss", $nm, $tg);
    header("Location: petugas.php?pesan=Data+tersimpan."); exit;
}
$e = ['id_petugas'=>0,'nama_petugas'=>'','tugas'=>''];
if (isset($_GET['edit'])) $e = mysqli_fetch_assoc(ambil($conn, "SELECT * FROM petugas WHERE id_petugas=?", "i", (int)$_GET['edit'])) ?: $e;
$judul = "Data Petugas";
include "header.php";
if ($pesan) echo "<div class='alert ok'>" . e($pesan) . "</div>";
if (is_admin()) { ?>
<form class="card form inline" method="post">
    <input type="hidden" name="id" value="<?= $e['id_petugas'] ?>">
    <input type="text" name="nama_petugas" placeholder="Nama petugas" value="<?= e($e['nama_petugas']) ?>" required>
    <?= pilihan('tugas', ['Pawang','Dokter Hewan','Pemberi Pakan','Kebersihan Kandang','Keamanan','Administrasi','Pemandu Wisata'], $e['tugas'], '-- tugas --') ?>
    <button class="btn"><?= $e['id_petugas'] ? 'Update' : 'Tambah' ?></button>
    <?php if ($e['id_petugas']) echo '<a class="btn abu" href="petugas.php">Batal</a>'; ?>
</form>
<?php } ?>
<div class="card"><table>
<tr><th>No</th><th>Nama Petugas</th><th>Tugas</th><?= is_admin() ? '<th>Aksi</th>' : '' ?></tr>
<?php $no = 1; $r = mysqli_query($conn, "SELECT * FROM petugas ORDER BY nama_petugas");
while ($p = mysqli_fetch_assoc($r)) { ?>
<tr><td><?= $no++ ?></td><td><?= e($p['nama_petugas']) ?></td><td><?= e($p['tugas']) ?></td>
<?php if (is_admin()) { ?><td><a class="btn sm kuning" href="?edit=<?= $p['id_petugas'] ?>">Edit</a> <a class="btn sm merah" href="?hapus=<?= $p['id_petugas'] ?>" onclick="return confirm('Hapus petugas ini?')">Hapus</a></td><?php } ?></tr>
<?php } ?>
</table></div>
<?php include "footer.php"; ?>
